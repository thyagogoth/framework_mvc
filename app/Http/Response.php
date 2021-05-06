<?php

namespace App\Http;

class Response {

	/**
	 * Código do status HTTP
	 * @var integer
	 */
	private $httpCode = 200;

	/**
	 * Cabeçalho do Response
	 * @var array
	 */
	private $headers = [];

	/**
	 * Tipo de conteúdo que será retornado
	 * @var string
	 */
	private $contentType = 'text/html';

	/**
	 * Conteúdo do Response'
	 * @var mixed
	 */
	private $content;

	/**
	 * Método responsável por iniciar a classe e definir os valores
	 * @param integer $httpCode
	 * @param mixed $content
	 * @param string $contentType
	 */
	public function __construct($httpCode, $content, $contentType = 'text/html') {
		$this->httpCode = $httpCode;
		$this->content = $content;
		$this->setContentType($contentType);
	}

	/**
	 * Método responsável por algerar o ContentType do response
	 * @param string $contentType
	 */
	public function setContentType($contentType) {
		$this->contentType = $contentType;
		$this->addHeader('Content-Type', $contentType);
	}

	/**
	 * Método responsável por adicionar um registro no cabeçalho de response
	 *
	 * @param string $key
	 * @param [type] $value
	 * @return void
	 */
	public function addHeader($key, $value) {
		$this->headers[$key] = $value;
	}

	/**
	 * Método responsável por enviar os headers para o navegador
	 */
	private function sendHeaders() {
		// Status
		http_response_code($this->httpCode);

		// Enviar os Headers
		foreach ($this->headers as $key => $value) {
			header("${key}: {$value}");
		}
	}

	/**
	 * Método responsável por enviar a resposta para o usuário
	 */
	public function sendResponse() {
		// Envia os Headers
		$this->sendHeaders();

		// Imprime o conteúdo
		switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;

			case 'application/json':
				echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				exit;
		}
	}

}