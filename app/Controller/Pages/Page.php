<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page {

	/**
	 * Método responsável por renderizar o topo da página
	 * @return string
	 */
	private static function getHeader() {
		return View::render('pages/header');
	}

	/**
	 * Método responsável por renderizar o rodape da página
	 * @return string
	 */
	private static function getFooter() {
		return View::render('pages/footer');
	}

	/**
	 * Método responsável por renderizar o layout de paginacao
	 *
	 * @param Request $request
	 * @param Pagination $obPagination
	 * @return string
	 */
	public static function getPagination($request, $obPagination)
	{
		// páginas
		$pages = $obPagination->getPages();

		// Verifica a quantidade de páginas
		if ( count($pages) <= 1) return '';

		$links = '';

		// URL atual sem os GETS
		$url = $request->getRouter()->getCurrentUrl();

		// GET
		$queryParams = $request->getQueryParams();

		// Renderiza os links
		foreach($pages as $page) {
			// Altera a página
			$queryParams['page'] = $page['page'];

			// LINK
			$link = $url.'?'.http_build_query($queryParams);
			
			// View
			$links .=  View::render('pages/pagination/link', [
				'page' => $page['page'],
				'link' => $link,
				'active' => $page['current'] ? 'active' : ''
			]);
		}

		// Renderiza Box de paginação
		return View::render('pages/pagination/box', [
			'links' => $links
		]);
	}

	/**
	 * Método responsável por retornar o conteúdo (view)
	 * da nossa estrutura do site (página genérica)
	 * @return string
	 */
	public static function getPage($title, $content) {

		return View::render('pages/page', [
			'header' => self::getHeader(),
			'title' => $title,
			'content' => $content,
			'footer' => self::getFooter(),
		]);

	}

}