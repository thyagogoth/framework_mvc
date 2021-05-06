<?php

namespace App\Http\Middleware;

use Exception;

class Queue {

    /**
     * Mapeamento de middlewares
     * @var array
     */
    private static $map = [];

    /**
     * Mapeamento de middlewares que serão carregados em todas as rotas
     * @param array 
     */
    private static $default = [];

	/**
	 * Fila de middlewares a serem executados
	 * @var array
	 */
	private $middlewares = [];

	/**
	 * Função de executação do controlador
	 *
	 * @var Closure
	 */
	private $controller;

	/**
	 * Argumentos da função do controlador
	 *
	 * @var array
	 */
	private $controllerArgs = [];

	/**
	 * Método responsável por construir a classe de fila de middleawres
	 *
	 * @param array $middlewares
	 * @param Closure $controller
	 * @param array $controllerArgs
	 */
	public function __construct($middlewares, $controller, $controllerArgs) {
		$this->middlewares = array_merge(self::$default, $middlewares);
		$this->controller = $controller;
		$this->controllerArgs = $controllerArgs;
	}

    /**
     * Método responsável por definir o mapeamento de middlwares
     *
     * @param array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

     /**
     * Método responsável por definir o mapeamento de middlwares padrões
     *
     * @param array $default
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }

    /**
     * Método responsável por executar o próximo nível da fila de middlewares 
     *
     * @param Request $request
     * @return Response
     */
    public function next($request) 
    {
        // Verifica se a fila está vazia
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        // Middleware
        $middleware = array_shift($this->middlewares);

        // Verifica o mapeamento
        if (!isset(self::$map[$middleware])) {
            throw new Exception("Problems when executing the request middleware", 500);
        }

        // next
        $queue = $this;
        $next = function($request) use($queue) {
            return $queue->next($request);
        };

        // Executa o middleware
        return (new self::$map[$middleware])->handle($request, $next);
    }
}