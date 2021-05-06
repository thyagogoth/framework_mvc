<?php
namespace App\Controller\Admin;

use \App\Model\Entity\User;
use \App\Utils\View;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page {

	/**
	 * Método esponsável por retornar a renderização da página de Login
	 * @param Request $request
	 * @param string $errorMessage
	 * @return string
	 */
	public static function getLogin($request, $errorMessage = null) {
		// Status
		$status = !is_null($errorMessage) ? Alert::getAlert('danger', $errorMessage) : '';

		// conteúdo da página de login
		$content = View::render('admin/login', [
			'status' => $status,
		]);

		// retorna a página completa
		return parent::getPage('Login - WDEV', $content);
	}

	/**
	 * Método responsável por definir o login do usuário
	 * @param Request $request
	 * @return void
	 */
	public static function setLogin($request) {
		// Dados do POST
		$postVars = $request->getPostVars();
		$email = $postVars['email'] ?? '';
		$senha = $postVars['senha'] ?? '';

		// busca o usuário pelo e-mail
		$obUser = User::getUserByEmail($email);
		if (!$obUser instanceof User) {
			return self::getLogin($request, 'E-mail ou senha inválidos');
		}

		//Verifica a senha do usuário
		if (!password_verify($senha, $obUser->senha)) {
			return self::getLogin($request, 'E-mail ou senha inválidos');
		}

        // Cria a sessão de login
        SessionAdminLogin::login($obUser);

        // Redireciona o usuário para a Home do admin
        $request->getRouter()->redirect('/admin');
	}

	/**
	 * Método responsável por deslogar o usuário
	 * @param Request $request
	 * @return void
	 */
	public static function setLogout($request)
	{
		  // Destrói a sessão de login
		  SessionAdminLogin::logout();

		  // Redireciona o usuário para a tela de login do admin
		  $request->getRouter()->redirect('/admin/login');
	}

}