<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatService
{
    public function chat(array $messages): string
    {
        $baseUrl = rtrim((string) config('ai.base_url'), '/');
        $apiKey  = (string) config('ai.api_key');
        $model   = (string) config('ai.model');

        if ($baseUrl === '' || $apiKey === '' || $model === '') {
            throw new \RuntimeException('AI config missing: base_url/api_key/model');
        }

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.6,
        ];

        try {
            /** @var Response $res */
            $res = Http::timeout(45)
                ->connectTimeout(10)
                ->withToken($apiKey)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->asJson()
                ->post($baseUrl . '/chat/completions', $payload);

            $res->throw();

            $json = $res->json();
            $content = data_get($json, 'choices.0.message.content');

            if (!is_string($content)) {
                Log::warning('AI response missing content', [
                    'base_url' => $baseUrl,
                    'model' => $model,
                    'status' => $res->status(),
                    'json' => $json,
                    'body_preview' => $this->preview($res->body()),
                ]);
                return '';
            }

            return trim($content);

        } catch (RequestException $e) {
            $status = $e->response?->status();
            $body   = $e->response?->body();

            Log::error('AI HTTP error', [
                'status' => $status,
                'base_url' => $baseUrl,
                'model' => $model,
                'body_preview' => $this->preview($body),
            ]);

            $short = $this->shortErrorFromBody($body);
            throw new \RuntimeException("AI request failed ({$status}): {$short}");

        } catch (\Throwable $e) {
            Log::error('AI unexpected error', [
                'error' => $e->getMessage(),
                'base_url' => $baseUrl,
                'model' => $model,
            ]);

            throw new \RuntimeException('AI request failed: ' . $e->getMessage());
        }
    }

    private function shortErrorFromBody(?string $body): string
    {
        if (!$body) return 'No response body';

        $decoded = json_decode($body, true);
        if (is_array($decoded)) {
            $msg = $decoded['error']['message'] ?? $decoded['message'] ?? null;
            if (is_string($msg) && $msg !== '') return $msg;
        }

        return $this->preview($body);
    }

    private function preview(?string $s, int $max = 300): string
    {
        if (!$s) return '';
        $plain = trim(strip_tags($s));
        if (mb_strlen($plain) > $max) return mb_substr($plain, 0, $max) . '...';
        return $plain;
    }
}
