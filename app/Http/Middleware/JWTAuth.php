<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;
use Exception;
use \Firebase\JWT\JWT;

class JWTAuth {

	/**
	 * Método responsável por retornar uma instância de usuário autenticado
	 * @param Request $request
	 * @return User
	 */
	private function getJWTAuthUser($request) {
		//Authorization JWT
	
		// Headers
		$headers = $request->getHeaders();
		
		// Token puro em JWT
		$jwt = isset($headers['Authorization']) ? str_replace('Bearer ','',$headers['Authorization']) : null;

		try {
			//Decode
			$decode = JWT::decode($jwt, getenv('JWT_SECRET_KEY'), ['HS256']);
		} catch (Exception $e) {
			throw new Exception('Token inválido', 403);
		}

		$email = $decode->email ?? '';
		$obUser = User::getUserByEmail($email);

		// Valida a senha e retorna o usuário
		return $obUser instanceof User ? $obUser : false;
	}

	/**
	 * Método responsável por validar o acesso via JWT
	 *
	 * @param Request $request
	 */
	private function auth($request) {
		// Obtem o usuario recebido
		if ( $obUser = $this->getJWTAuthUser($request) ) {
			$request->user = $obUser;
			return true;
		}

		throw new Exception("Access denied!", 403);
	}

	/**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {

		// Realiza a validação do acesso via JWT
		$this->auth($request);

		// Executa o próximo nível do middleware
		return $next($request);
	}

}