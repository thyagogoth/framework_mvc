<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache {

	/**
	 * Metodo responsável por veriicar se a request atual é cacheável
	 *
	 * @param Request $request
	 * @return boolean
	 */
	private function isCacheable($request) {
		// Valida o tempo de cache
		if (getenv('CACHE_TIME') <= 0) {
			return false;
		}

		// Valida o método da requisição
		if ($request->getHttpMethod() !== 'GET') {
			return false;
		}

		// Valida o Header de cache (OPCIONAL)
		$hearders = $request->getHeaders();
		if (isset($hearders['Cache-Control']) and $hearders['Cache-Control'] == 'no-cache') {
			return false;
		}

		return true;
	}

	/**
	 * Método responsável por retornar a hash do cache
	 *
	 * @param Request $request
	 * @return string
	 */
	private function getHash($request) {
		// obtem URI da rota
		$uri = $request->getRouter()->getUri();

		// Query Params;
		$queryParams = $request->getQueryParams();
		$uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

		return preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/'));
	}

	/**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {

		// Verifica se a Request atual é cacheaveç
		if (!$this->isCacheable($request)) {
			return $next($request);
		}

		// hash do cache
		$hash = $this->getHash($request);

		// retorna os dados da cache
		return CacheFile::getCache($hash, getenv('CACHE_TIME'), function () use($request, $next){
			return $next($request);
		});

		// Executa o próximo nível do middleware
		return $next($request);
	}

}