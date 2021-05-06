<?php

namespace App\Http\Middleware;

class Api {

	/**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {
		// Altera o content type para JSON
		$request->getRouter()->setContentType('application/json');

		// Executa o próximo nível do middleware
		return $next($request);
	}

}