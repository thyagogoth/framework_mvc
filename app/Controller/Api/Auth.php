<?php

namespace App\Controller\Api;

use App\Model\Entity\User;
use Exception;
use \Firebase\JWT\JWT;

class Auth extends Api {

    private static $token;

    /**
     * Método responsável por gerar um token JWT
     *
     * @param Request $request
     * @return array
     */
    public static function generateToken($request) {

        // Post Vars
        $postVars = $request->getPostVars();

        // Valida os campos obrigatórios
        if ( !isset($postVars['email']) || !isset($postVars['senha'])) {
            throw new Exception("'Email' and 'senha' fields are required", 400);
        }

        // Busca o usuário pelo email
        $obUser = User::getUserByEmail($postVars['email']);
        if ( !$obUser instanceof User) {
            throw new Exception("'Email' or 'senha' invalid!");
        }

        // Valida a senha do usuário
        if ( !password_verify($postVars['senha'], $obUser->senha) ) {
            throw new Exception("'Email' or 'senha' invalid!");
        }

        // Payload JWT
        $payload = [
            'email' => $obUser->email
        ];

        // Retorna o token gerado
        return [
            'token' => JWT::encode($payload, getenv('JWT_SECRET_KEY'))
        ];
    }

}