<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Внутренний API сервис - вызывает API через внутренний sub-request
 */
class ApiService
{
    private function getToken(): ?string
    {
        return Session::get('api_token');
    }

    private function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = '/api/v1/' . ltrim($endpoint, '/');

        try {
            $request = Request::create($url, strtoupper($method), $data, [], [], [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]);

            $token = $this->getToken();
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }

            // Используем Router напрямую, минуя HTTP kernel и middleware типа CSRF
            /** @var Router $router */
            $router = app(Router::class);
            $response = $router->dispatch($request);

            $content = $response->getContent();
            $result = json_decode($content, true);

            return $result ?? ['success' => false, 'message' => 'Пустой ответ от API'];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ];
        } catch (\Exception $e) {
            Log::error("API {$method} {$url} Error: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function get(string $endpoint, array $params = []): array
    {
        return $this->makeRequest('GET', $endpoint, $params);
    }

    public function post(string $endpoint, array $data = [], bool $hasFile = false): array
    {
        if ($hasFile) {
            return $this->makeFileRequest('POST', $endpoint, $data);
        }
        return $this->makeRequest('POST', $endpoint, $data);
    }

    public function put(string $endpoint, array $data = [], bool $hasFile = false): array
    {
        if ($hasFile) {
            return $this->makeFileRequest('PUT', $endpoint, $data);
        }
        return $this->makeRequest('PUT', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('DELETE', $endpoint, $data);
    }

    private function makeFileRequest(string $method, string $endpoint, array $data): array
    {
        $url = '/api/v1/' . ltrim($endpoint, '/');

        try {
            $files = [];
            $fields = [];

            foreach ($data as $key => $value) {
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $files[$key] = $value;
                } else {
                    $fields[$key] = $value;
                }
            }

            $actualMethod = $method === 'PUT' ? 'POST' : $method;
            if ($method === 'PUT') {
                $fields['_method'] = 'PUT';
            }

            $request = Request::create($url, $actualMethod, $fields, [], $files, [
                'HTTP_ACCEPT' => 'application/json',
            ]);

            $token = $this->getToken();
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }

            $router = app(Router::class);
            $response = $router->dispatch($request);
            $result = json_decode($response->getContent(), true);

            return $result ?? ['success' => false, 'message' => 'Пустой ответ'];
        } catch (\Exception $e) {
            Log::error("API FILE {$method} {$url} Error: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
