<?php
namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page {

	 /**
     * Método responsável por obter a renderização dos itens de usuários para a página
     * @param Request $request
     * @param Pagination &$obPagination
     * @return string
     */
    private static function getUserItems($request, &$obPagination)
    {
        // Usuários
        $itens = '';

        // Quantidade total de registros
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(id) AS qtd')->fetchObject()->qtd;

		// Define a página atual do projeto
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // Instância de Paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, ITEMS_PER_PAGE);

        // Resultados da página
        $results = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

        // Renderiza o item
        while($obUser = $results->fetchObject(EntityUser::class)){
            // View de usuários
            $itens .=  View::render('admin/modules/users/items', [
				'id' => $obUser->id,
                'nome' => $obUser->nome,
				'email' => $obUser->email
            ]);
        }
         
        return $itens;
    }

	/**
	 * Método responsável por renderizar a View de listagem de usuários
	 * @param Request $request
	 * @return string
	 */
	public static function getUsers($request) {

		// View de usuários
        $content =  View::render('admin/modules/users/index', [
            'items' 	 => self::getUserItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
			'status' => self::getStatus($request)
        ]);

		// $content = View::render('admin/modules/users/index', []);

		// retorna a página completa
		return parent::getPanel('Usuários - Admin', $content, 'users');
	}

	/**
	 * Método responsável por retornar o formulário de cadastro de um novo usuário
	 * 
	 * @param [type] $request
	 * @return void
	 */
	public static function getNewUser($request){
		// Conteúdo do formulário
        $content =  View::render('admin/modules/users/form', [
			'title' => 'Cadastrar usuário',
			'nome' => null,
			'email' => null,
			'status' => self::getStatus($request)
        ]);

		// retorna a página completa
		return parent::getPanel('Cadastrar usuário - Admin', $content, 'users');
	}

	/**
	 * Método responsável por cadastrar um novo usuário
	 *
	 * @param [type] $request
	 * @return void
	 */
	public static function setNewUser($request){

		// POST
		$postVars = $request->getPostVars();

		$nome = $postVars['nome'] ?? null;
		$email = $postVars['email'] ?? null;
		$senha = $postVars['senha'] ?? null;

		// Valida o e-mail do usuário
		$obUser = EntityUser::getUserByEmail($email);
		if ( $obUser instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users/new?status=duplicated');
		}

		// Nova instância de User
		$obUser = new EntityUser;
		$obUser->nome = $nome;
		$obUser->email = $email;
		$obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
		$obUser->create();

		// Redireciona o usuário
		$request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
	}

	/**
	 * Método responsável por retornar a mensagem de status
	 * @param Request $request
	 * @return string
	 */
	private static function getStatus($request)
	{
		// Query Params
		$queryParams = $request->getQueryParams();
		
		// status
		if ( !isset($queryParams['status'])) return '';

		// Mensagens de status
		switch($queryParams['status']) {
			case 'created':
				return Alert::getSuccess('Usuário criado com sucesso', 201);
				break;
			case 'updated':
				return Alert::getSuccess('Usuário atualizado com sucesso', 201);
				break;
			case 'deleted':
				return Alert::getSuccess('Usuário excluído com sucesso', 201);
				break;
			case 'duplicated':
				return Alert::getError('Este e-mail já está sendo utilizado', 401);
				break;
		}
	}

	/**
	 * Método responsável por retornar o formulário de edição de um usuário
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function getEditUser($request, $id) {

		// obtém o usuário do banco de dados
		$obUser = EntityUser::getUserById($id);

		// Valida instância
		if ( !$obUser instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users?status=missed');
		}

		// Conteúdo do formulário
        $content =  View::render('admin/modules/users/form', [
			'title'  => 'Editar usuário',
			'nome'   => $obUser->nome,
			'email'  => $obUser->email,
			'status' => self::getStatus($request)
        ]);

		// retorna a página completa
		return parent::getPanel('Editar usuário - Admin', $content, 'users');
	}

	/**
	 * Método responsável por gravar a atualização de um usuário
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function setEditUser($request, $id) {

		// obtém o usuário do banco de dados
		$obUser = EntityUser::getUserById($id);

		// Valida instância
		if ( !$obUser instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users');
		}

		// Post Vars 
		$postVars = $request->getPostVars();

		// Valida a instância
		$nome = $postVars['nome'] ?? null;
		$email = $postVars['email'] ?? null;
		$senha = $postVars['senha'] ?? null;

		// Valida o e-mail do usuário
		$obUserEmail = EntityUser::getUserByEmail($email);
		if ( $obUserEmail instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users/new?status=duplicated');
		}


		// atualiza a instância
		$obUser->nome = $postVars['nome'] ?? $obUser->nome;
		$obUser->email = $postVars['email'] ?? $obUser->email;
		$obUser->senha = password_hash($postVars['email'], PASSWORD_DEFAULT) ?? $obUser->senha;

		$obUser->update();

		// retorna a 
		$request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
	}

	/**
	 * Método responsável por retornar o formulário de exclusão de um usuário
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function getDeleteUser($request, $id) {

		// obtém o usuário do banco de dados
		$obUser = EntityUser::getUserById($id);

		// Valida instância
		if ( !$obUser instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users');
		}
		// Conteúdo do formulário
        $content =  View::render('admin/modules/users/delete', [
			'nome' => $obUser->nome,
			'email' => $obUser->email
        ]);

		// retorna a página completa
		return parent::getPanel('Excluir usuário - Admin', $content, 'users');
	}

	/**
	 * Método responsável por excluir a instância de User do BD
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function setDeleteUser($request, $id) {

		// obtém o usuário do banco de dados
		$obUser = EntityUser::getUserById($id);

		// Valida instância
		if ( !$obUser instanceof EntityUser) {
			$request->getRouter()->redirect('/admin/users');
		}

		// Exclui o usuário
		$obUser->delete();

		// Redireciona o usuário
		$request->getRouter()->redirect('/admin/users?status=deleted');
	}
}