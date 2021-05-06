<?php
namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page {

	/**
	 * Método responsável por renderizar a View de Home do painel
	 * @param Request $request
	 * @return string
	 */
	public static function getHome($request) {
		$content = View::render('admin/modules/home/index', []);

		// retorna a página completa
		return parent::getPanel('Home - Admin', $content, 'home');
	}

	
}