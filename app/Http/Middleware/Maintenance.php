<?php

namespace App\Http\Middleware;

class Maintenance {

	/**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {
		// Verifica o estado de manutenção da página
		if (getenv('MAINTENANCE') == 'true') {
			throw new \Exception("PÁGINA EM MANUTENÇÃO!<br/>Tente novamente mais tarde", 200);
		}

		// Executa o próximo nível do middleware
		return $next($request);
	}

}