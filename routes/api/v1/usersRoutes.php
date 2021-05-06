<?php

use \App\Controller\Api;
use \App\Http\Response;



// Rota de listagem de usuários
$obRouter->get('/api/v1/users', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request) {
		return new Response(200, Api\User::getUsers($request), 'application/json');
	},
]);

// Rota de consulta do usuário atual
$obRouter->get('/api/v1/users/me', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD')
	],
	function ($request) {
		return new Response(200, Api\User::getCurrentUser($request) , 'application/json');
	},
]);

// Rota de consulta individual de usuários
$obRouter->get('/api/v1/users/{id}', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request, $id) {
		return new Response(200, Api\User::getUser($request, $id), 'application/json');
	},
]);

// Rota de cadastro de usuários
$obRouter->post('/api/v1/users', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request) {
		return new Response(201, Api\User::setNewUser($request), 'application/json');
	},
]);

// Rota de cadastro de usuários
$obRouter->put('/api/v1/users/{id}', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request, $id) {
		return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
	},
]);

// Rota de exclusão de usuários
$obRouter->delete('/api/v1/users/{id}', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request, $id) {
		return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
	},
]);