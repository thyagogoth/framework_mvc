<?php

use \App\Http\Response;
use \App\Controller\Pages;

// Rota Home
$obRouter->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

// Rota Sobre
$obRouter->get('/sobre', [
    'middlewares' => [
        'cache'
    ],
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);

// Rota Depoimentos (list)
$obRouter->get('/depoimentos', [
    'middlewares' => [
        'cache'
    ],
    function($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

// Rota Depoimentos (create)
$obRouter->post('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);