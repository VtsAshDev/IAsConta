<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiService
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'GEMINI_API')]
        private string $apiKey,
    ){
    }

    public function __invoke(string $message)
    {
        $promptInstruction = "
            Você é um assistente financeiro. Analise o texto do usuário e extraia o gasto.
            Retorne um JSON com as chaves: 'descricao' (string), 'valor' (float), 'categoria' (string), 'moeda' (string).
            Se não identificar um gasto claro, retorne null no JSON.
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
