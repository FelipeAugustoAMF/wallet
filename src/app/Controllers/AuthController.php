<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function register()
    {
        $data = $this->request->getJSON(true);

        if (! $this->validate([
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $id = $userModel->insert([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        model('WalletModel')->insert(['user_id' => $id, 'balance' => 0]);

        return $this->respondCreated(['message' => 'User registered']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $user = model('UserModel')->where('email', $data['email'])->first();

        if (! $user || ! password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized('Invalid credentials');
        }

        session()->set(['user_id' => $user['id']]);

        return $this->respond(['message' => 'Logged in']);
    }

    public function transaction()
    {
        $data = $this->request->getJSON(true);
        $user = model('UserModel')->where('email', $data['email'])->first();

        if (! $user || ! password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized('Invalid credentials');
        }

        session()->set(['user_id' => $user['id']]);

        return $this->respond(['message' => 'Logged in']);
    }

    public function logout()
    {
        session()->destroy();
        return $this->respond(['message' => 'Logged out']);
    }
}
