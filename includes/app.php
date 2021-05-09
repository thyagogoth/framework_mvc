<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\View;
use WilliamCosta\DotEnv\Environment;
use WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

// Carrega variáveis de ambiente
Environment::load(__DIR__.'/../');

// Define as configurações de Banco de dados
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT'),
);

// Define a URL base do projeto
define('URL', getenv('URL'));
define('ITEMS_PER_PAGE', getenv('ITEMS_PER_PAGE'));

// Define o valor padrão das variáveis
View::init([
    'URL' => URL,
    'ITEMS_PER_PAGE' => ITEMS_PER_PAGE
]);
    
// Define o mapeamento de middlewares
MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class,
    'api' => \App\Http\Middleware\Api::class,
    'user-basic-auth' => \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth' => \App\Http\Middleware\JWTAuth::class,
    'cache' => \App\Http\Middleware\Cache::class,
] );

// Define o mapeamento de middlewares padrões
MiddlewareQueue::setDefault([
    'maintenance'
]);