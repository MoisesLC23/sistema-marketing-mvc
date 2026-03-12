<?php

class OpenAIClient {
    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey, $baseUrl = 'https://api.openai.com/v1') {
        $this->apiKey  = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Llama al endpoint /chat/completions
     */
    public function chatCompletions(array $payload): array {
        $url = $this->baseUrl . '/chat/completions';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $this->apiKey,
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_TIMEOUT => 60,
        ]);

        $res = curl_exec($ch);

        if ($res === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('Error al llamar a OpenAI: ' . $error);
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($res, true);

        if ($code >= 400) {
            $msg = $data['error']['message'] ?? 'Error desconocido';
            throw new RuntimeException("OpenAI devolvió HTTP $code: $msg");
        }

        return $data;
    }
}
