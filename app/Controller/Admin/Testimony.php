<?php
namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page {

	 /**
     * Método responsável por obter a renderização dos itens de depoimentos para a página
     * @param Request $request
     * @param Pagination &$obPagination
     * @return string
     */
    private static function getTestimonyItems($request, &$obPagination)
    {
        // Depoimentos
        $itens = '';

        // Quantidade total de registros
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(id) AS qtd')->fetchObject()->qtd;

		// Define a página atual do projeto
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // Instância de Paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, ITEMS_PER_PAGE);

        // Resultados da página
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        // Renderiza o item
        while($obTestimony = $results->fetchObject(EntityTestimony::class)){
            // View de depoimentos
            $itens .=  View::render('admin/modules/testimonies/items', [
				'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'created_at' => date('d/m/Y H:i', strtotime($obTestimony->created_at))
            ]);
        }
         
        return $itens;
    }

	/**
	 * Método responsável por renderizar a View de listagem de depoimentos
	 * @param Request $request
	 * @return string
	 */
	public static function getTestimonies($request) {

		// View de depoimentos
        $content =  View::render('admin/modules/testimonies/index', [
            'items' 	 => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
			'status' => self::getStatus($request)
        ]);

		// $content = View::render('admin/modules/testimonies/index', []);

		// retorna a página completa
		return parent::getPanel('Depoimentos - Admin', $content, 'testimonies');
	}

	/**
	 * Método responsável por retornar o formulário de cadastro de um novo depoimento
	 * 
	 * @param [type] $request
	 * @return void
	 */
	public static function getNewTestimony($request){
		// Conteúdo do formulário
        $content =  View::render('admin/modules/testimonies/form', [
			'title' => 'Cadastrar depoimento',
			'nome' => null,
			'mensagem' => null,
			'status' => null
        ]);

		// retorna a página completa
		return parent::getPanel('Novo depoimento - Admin', $content, 'testimonies');
	}

	/**
	 * Método responsável por cadastrar um novo depoimento
	 *
	 * @param [type] $request
	 * @return void
	 */
	public static function setNewTestimony($request){

		// POST
		$postVars = $request->getPostVars();
		
		// Nova instância de Testimony
		$obTestimony = new EntityTestimony;
		$obTestimony->nome = $postVars['nome'] ?? '';
		$obTestimony->mensagem = $postVars['mensagem'] ?? '';
		
		$obTestimony->create();

		// Redireciona o usuário
		$request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
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
				return Alert::getSuccess('Depoimento criado com sucesso', 201);
				break;
			case 'updated':
				return Alert::getSuccess('Depoimento atualizado com sucesso', 201);
				break;
			case 'deleted':
				return Alert::getSuccess('Depoimento excluido com sucesso', 201);
				break;
		}
	}

	/**
	 * Método responsável por retornar o formulário de edição de um depoimento
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function getEditTestimony($request, $id) {

		// obtém o depoimento do banco de dados
		$obTestimony = EntityTestimony::getTestimonyById($id);

		// Valida instância
		if ( !$obTestimony instanceof EntityTestimony) {
			$request->getRouter()->redirect('/admin/testimonies');
		}

		// Conteúdo do formulário
        $content =  View::render('admin/modules/testimonies/form', [
			'title' => 'Editar depoimento',
			'nome' => $obTestimony->nome,
			'mensagem' => $obTestimony->mensagem,
			'status' => self::getStatus($request)
        ]);

		// retorna a página completa
		return parent::getPanel('Editar depoimento - Admin', $content, 'testimonies');
	}

	/**
	 * Método responsável por gravar a atualização de um depoimento
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function setEditTestimony($request, $id) {

		// obtém o depoimento do banco de dados
		$obTestimony = EntityTestimony::getTestimonyById($id);

		// Valida instância
		if ( !$obTestimony instanceof EntityTestimony) {
			$request->getRouter()->redirect('/admin/testimonies');
		}

		// Post Vars 
		$postVars = $request->getPostVars();

		// atualiza a instância
		$obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
		$obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;

		$obTestimony->update();

		// retorna a 
		$request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
	}


	/**
	 * Método responsável por retornar o formulário de exclusão de um depoimento
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function getDeleteTestimony($request, $id) {

		// obtém o depoimento do banco de dados
		$obTestimony = EntityTestimony::getTestimonyById($id);

		// Valida instância
		if ( !$obTestimony instanceof EntityTestimony) {
			$request->getRouter()->redirect('/admin/testimonies');
		}

		// Conteúdo do formulário
        $content =  View::render('admin/modules/testimonies/delete', [
			'nome' => $obTestimony->nome,
			'mensagem' => $obTestimony->mensagem
        ]);

		// retorna a página completa
		return parent::getPanel('Excluir depoimento - Admin', $content, 'testimonies');
	}

	/**
	 * Método responsável por excluir a instância de Testimony do BD
	 * 
	 * @param Request $request
	 * @param integer $id
	 * @return string
	 */
	public static function setDeleteTestimony($request, $id) {

		// obtém o depoimento do banco de dados
		$obTestimony = EntityTestimony::getTestimonyById($id);

		// Valida instância
		if ( !$obTestimony instanceof EntityTestimony) {
			$request->getRouter()->redirect('/admin/testimonies');
		}

		// Exclui o depoimento
		$obTestimony->delete();

		// Redireciona o usuário
		$request->getRouter()->redirect('/admin/testimonies?status=deleted');
	}
}