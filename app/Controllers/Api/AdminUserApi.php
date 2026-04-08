<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminUserApi extends BaseController
{
    public function create(): ResponseInterface
    {
        if (! $this->request->is('post')) {
            return $this->response->setStatusCode(405)->setJSON([
                'status'  => 'error',
                'message' => 'Method not allowed',
            ]);
        }

        $apiKey = (string) $this->request->getHeaderLine('X-API-KEY');
        $expectedApiKey = (string) env('app.adminApiKey');

        if ($expectedApiKey === '' || ! hash_equals($expectedApiKey, $apiKey)) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Invalid API key',
            ]);
        }

        $payload = $this->request->getJSON(true);
        if (! is_array($payload)) {
            $payload = $this->request->getPost();
        }

        $validation = service('validation');
        $validation->setRules([
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[191]|is_unique[users.email]',
            'phone'     => 'required|regex_match[/^\\+?[0-9]{8,20}$/]',
            'password'  => 'required|min_length[8]|max_length[72]|regex_match[/^(?=.*[A-Za-z])(?=.*\\d).+$/]',
        ]);

        if (! $validation->run($payload)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validation->getErrors(),
            ]);
        }

        $userModel = new UserModel();
        $id = $userModel->insert([
            'full_name'     => (string) $payload['full_name'],
            'email'         => (string) $payload['email'],
            'phone'         => (string) $payload['phone'],
            'password_hash' => password_hash((string) $payload['password'], PASSWORD_DEFAULT),
            'role'          => 'admin',
        ]);

        if (! $id) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create admin user',
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'status'  => 'success',
            'message' => 'Admin created',
            'data'    => ['id' => $id],
        ]);
    }
}
