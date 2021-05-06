<?php

use \App\Controller\Api;
use \App\Http\Response;

// Rota de listagem de depoimentos
$obRouter->get('/api/v1/testimonies', [
	'middlewares' => [
		'api',
	],
	function ($request) {
		return new Response(200, Api\Testimony::getTestimonies($request), 'application/json');
	},
]);

// Rota de consulta individual de depoimentos
$obRouter->get('/api/v1/testimonies/{id}', [
	'middlewares' => [
		'api',
	],
	function ($request, $id) {
		return new Response(200, Api\Testimony::getTestimony($request, $id), 'application/json');
	},
]);

// Rota de cadastro de depoimentos
$obRouter->post('/api/v1/testimonies', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request) {
		return new Response(201, Api\Testimony::setNewTestimony($request), 'application/json');
	},
]);

// Rota de cadastro de depoimentos
$obRouter->put('/api/v1/testimonies/{id}', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request, $id) {
		return new Response(200, Api\Testimony::setEditTestimony($request, $id), 'application/json');
	},
]);

// Rota de exclusão de depoimentos
$obRouter->delete('/api/v1/testimonies/{id}', [
	'middlewares' => [
		'api',
		getenv('AUTHENTICATION_METHOD'),
	],
	function ($request, $id) {
		return new Response(200, Api\Testimony::setDeleteTestimony($request, $id), 'application/json');
	},
]);