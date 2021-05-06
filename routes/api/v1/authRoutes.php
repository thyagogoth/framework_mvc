<?php

use App\Controller\Api\Auth;
use \App\Http\Response;

// Rota de Autorização da API
$obRouter->post('/api/v1/auth', [
    'middlewares' => [
        'api'
    ],
    function($request) {
        return new Response(201, Auth::generateToken($request), 'application/json');
    }
]);