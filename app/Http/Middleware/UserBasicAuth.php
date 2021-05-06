<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;
use Exception;

class UserBasicAuth {

	/**
	 * Método responsável por retornar uma instância de usuário autenticado
	 *
	 * @return User
	 */
	private function getBasicAuthUser() {
		//Authorization Basic
		// Verifica a existência dos dados de acesso
		if ( !isset($_SERVER['PHP_AUTH_USER'])  || !isset($_SERVER['PHP_AUTH_PW'])) {
			return false;
		}

		$obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

		// Verifica instância
		if ( !$obUser instanceof User) {
			return false;
		}

		// Valida a senha e retorna o usuário

		return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
	}

	/**
	 * Método responsável por validar o acesso via Basic Auth
	 *
	 * @param Request $request
	 */
	private function basicAuth($request) {
		// Obtem o usuario recebido
		if ( $obUser = $this->getBasicAuthUser() ) {
			$request->user = $obUser;
			return true;
		}

		throw new Exception("Invalid user", 403);
	}

	/**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {

		// Realiza a validação do acesso via Basic Auth
		$this->basicAuth($request);

		// Executa o próximo nível do middleware
		return $next($request);
	}

}