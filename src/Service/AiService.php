<?php

namespace App\Service;

use App\Service\CategoryService\CategoryService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiService
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'GEMINI_API')]
        private string $apiKey,
        private CategoryService $categoryService
    ){
    }

    public function __invoke(string $message)
    {

        $categories = $this->categoryService->findAllAsText();

        $promptInstruction = "
            Você é um assistente financeiro de um app 'for dummies'.
            Sua tarefa é converter frases em dados estruturados.

            LISTA DE CATEGORIAS PERMITIDAS: [{$categories}].

            REGRAS:
            1. O campo 'categoria' DEVE ser EXATAMENTE um dos nomes da lista acima.
            2. Se o gasto não se encaixar perfeitamente, escolha a categoria mais próxima ou 'Outros'.
            3. No campo 'descricao', limpe o texto (Ex: 'Comi um burger' vira 'Burger').
            4. Se o texto não for um gasto (ex: 'Olá', 'Tudo bem?'), retorne apenas 'null'.
            5. Retorne APENAS o JSON, sem explicações.

            FORMATO JSON:
            {
              \"descricao\": \"string\",
              \"valor\": float,
              \"categoria\": \"string\",
            }
        ";

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key=' . $this->apiKey;

        try {
            $response = $this->client->request(
                'POST',
                $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'generationConfig' => [
                            'responseMimeType' => 'application/json'
                        ],
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $promptInstruction . "\n\nTEXTO DO USUÁRIO: " . $message],
                                ]
                            ]
                        ]
                    ],
                ]
            );

            $data = $response->toArray();

            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return "Erro na API ou limite atingido.";
            }

            $rawText = $data['candidates'][0]['content']['parts'][0]['text'];

            $gasto = json_decode($rawText, true);

            if ($gasto && isset($gasto['valor']) && $gasto['valor'] > 0){
                return sprintf(
                    "✅ Gasto de R$ %.2f registrado!\nItem: %s\nCategoria: %s",
                    $gasto['valor'],
                    $gasto['descricao'],
                    $gasto['categoria']
                );
            }

        } catch (\Exception $e) {
            return "Erro ao processar: " . $e->getCode();
        }

        return "Não entendi isso como um gasto. Tente: 'Uber 20 reais'.";
    }
}
