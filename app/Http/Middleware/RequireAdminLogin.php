<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin {

    /**
	 * Método responsável por executar o middle
	 *
	 * @param Request $request
	 * @param Closure $next
	 * @return Response
	 */
	public function handle($request, $next) {

        // Verifica se o usuário está logado
        if ( !SessionAdminLogin::isLogged() ) {
            $request->getRouter()->redirect('/admin/login');
        }

        // continua a execução
        return $next($request);
        
	}

}