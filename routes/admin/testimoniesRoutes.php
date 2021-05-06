<?php

use \App\Http\Response;
use \App\Controller\Admin; 

// Rota de listagem de depoimentos
$obRouter->get('/admin/testimonies', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

// Rota de listagem de depoimentos
$obRouter->get('/admin/testimonies', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

// Rota de cadastro de depoimentos
$obRouter->get('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

// Rota de cadastro de depoimentos (POST)
$obRouter->post('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);

// Rota de edição de depoimentos
$obRouter->get('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
    }
]);

// Rota de edição de depoimentos POST
$obRouter->post('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);

// Rota de exclusão de depoimentos
$obRouter->get('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
    }
]);

// Rota de exclusão de depoimentos POST
$obRouter->post('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
    }
]);