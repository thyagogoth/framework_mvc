<?php

namespace App\Utils\Cache;

class File {

	/**
	 * Método responsável por retornar o caminho até o arquivo de cache
	 *
	 * @param string $hash
	 * @return string
	 */
	private static function getFilePath($hash) {
		// Diretório de cache
		$dir = getenv('CACHE_DIR');
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true); // true cria a pasta de forma recusiva
		}

        // retorna o caminho até o arquivo
        return $dir. '/'. $hash;
	}

	/**
	 *  Método responsável por guardar informações no cache
	 *
	 * @param string $hash
	 * @param mixed $content
	 * @return boolean
	 */
	private static function storageCache($hash, $content) {
		// Converte o retorno para um dado serializado
		$serialize = serialize($content);

		// Obtém o caminho (absoluto) até o arquivo de cache
		$cacheFile = self::getFilePath($hash);

        // Grava as informações no arquivo de cache
        return file_put_contents($cacheFile, $serialize);
	}

    /**
     * Método responsável por retornar o conteúdo gravado no cache
     *
     * @param string $hash
     * @param integet $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration) {
        // obtém o caminho do arquivo
        $cacheFile = self::getFilePath($hash);

        // verifica a existência do arquivo
        if ( !file_exists($cacheFile)) return false;

        // Valida a expiração do arquivo de cache (Modification Time)
        $createTime = filectime($cacheFile);
        $diffTime = time() - $createTime;
        if ( $diffTime > $expiration) return false;

        // Retorna o dado real
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

	/**
	 * Método responsável por obter uma  informação do cache
	 *
	 * @param string $hash
	 * @param integer $expiration
	 * @param Closure $function
	 * @return mixed
	 */
	public static function getCache($hash, $expiration, $function) {

        // verifica o conteúdo gravado
        if ( $content = self::getContentCache($hash, $expiration) ) {
            return $content;
        }

		// Execução da função;
		$content = $function();

		// Grava o retorno no cache
		self::storageCache($hash, $content);

		// Retorna o conteúdo;
		return $content;
	}

}