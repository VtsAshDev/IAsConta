<?php

namespace App\Service;

use App\Service\CategoryService\CategoryService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class AiService
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'GEMINI_API')]
        private string $apiKey,
        private CategoryService $categoryService,
        private LoggerInterface $logger
    ){
    }

    public function __invoke(string $message): ?array
    {
        $categories = $this->categoryService->findAllAsText();

        $promptInstruction = "
            Você é um assistente financeiro.
            Sua tarefa é converter frases em dados estruturados (JSON).

            LISTA DE CATEGORIAS PERMITIDAS: [{$categories}].

            REGRAS:
            1. O campo 'categoria' DEVE ser EXATAMENTE um dos nomes da lista acima.
            2. No campo 'descricao', limpe o texto.
            3. Se o texto não for um gasto (ex: 'Olá'), retorne null.
            4. Retorne APENAS o JSON puro.

            FORMATO JSON:
            {
              \"descricao\": \"string\",
              \"valor\": \"string\",
              \"categoria\": \"string\"
            }
        ";

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key=' . $this->apiKey;

        try {
            $response = $this->client->request('POST', $url, [
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $promptInstruction . "\n\nTEXTO DO USUÁRIO: " . $message]]]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json'
                    ]
                ]
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception("Erro na API Gemini: " . $response->getStatusCode());
            }

            $data = $response->toArray();
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$rawText) {
                return null;
            }

            $cleanJson = preg_replace('/^```json|```$/m', '', $rawText);
            $gasto = json_decode(trim($cleanJson), true);

            if (is_array($gasto) && isset($gasto['valor']) && (float)$gasto['valor'] > 0) {
                return [
                    'valor' => (float)$gasto['valor'],
                    'descricao' => $gasto['descricao'] ?? 'Gasto sem descrição',
                    'categoria' => $gasto['categoria'] ?? 'Outros',
                ];
            }

        } catch (\Exception $e) {
            $this->logger->error("Erro no AiService: " . $e->getMessage());
            throw $e;
        }

        return null;
    }
}
