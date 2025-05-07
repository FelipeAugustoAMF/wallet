<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

/**
 * UserController
 *
 * Endpoints focados no usuário logado.
 *  - GET /api/user           -> informações básicas do perfil
 *  - PUT /api/user/password  -> troca de senha
 */
class UserController extends ResourceController
{
    protected $format = 'json';

    /**
     * Recupera o id da sessão ou responde 401.
     */
    private function requireAuth()
    {
        $uid = session('user_id');
        return $uid ?: $this->failUnauthorized('Login required');
    }

    /**
     * GET /api/user
     * Devolve { id, name, email } do usuário autenticado.
     */
    public function showProfile()
    {
        if (! is_numeric($uid = $this->requireAuth())) {
            return $uid; // já é uma ResponseInterface com 401
        }

        $user = model('UserModel')->select('id, name, email')
            ->find($uid);
        if (! $user) {
            return $this->failNotFound('User not found');
        }
        return $this->respond($user);
    }

    /**
     * PUT /api/user/password
     * Body: { currentPass, newPass }
     */
    public function changePassword()
    {
        if (! is_numeric($uid = $this->requireAuth())) {
            return $uid;
        }

        $data = $this->request->getJSON(true);
        $current = $data['currentPass'] ?? '';
        $new     = $data['newPass']     ?? '';

        if (strlen($new) < 6) {
            return $this->failValidationErrors('Nova senha deve ter ao menos 6 caracteres');
        }

        $user = model('UserModel')->find($uid);

        if (! password_verify($current, $user['password'])) {
            return $this->failValidationErrors('Senha atual incorreta');
        }

        model('UserModel')->update($uid, [
            'password' => password_hash($new, PASSWORD_DEFAULT),
        ]);

        return $this->respond(['message' => 'Senha atualizada']);
    }
}
