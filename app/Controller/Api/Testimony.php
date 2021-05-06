<?php
namespace App\Controller\Api;

use App\Model\Entity\Testimony as EntityTestimony;
use Exception;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api {

    /**
     * Método responsável por retornar os depoimentos cadastrados
     *
     * @param Request $request
     * @return array
     */
    public static function getTestimonies($request)
    {
        return [
            'data' => self::getTestimonyItems($request, $obPagination),
            'pages' => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por obter a renderização dos itens de depoimentos para a página
     * @param Request $request
     * @param Pagination &$obPagination
     * @return array
     */
    private static function getTestimonyItems($request, &$obPagination)
    {
        // Depoimentos
        $itens = [];

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
            // array de depoimentos
            $itens[] =  [
                'id' => (int) $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'created_at' => $obTestimony->created_at
            ];
        }
         
        return $itens;
    }

    /**
     * Método responsável por retornar os detalhes de um depoimento
     *
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getTestimony($request, $id) {
        // Valida o Item 
        if ( !is_numeric($id) ) {
            throw new Exception('Invalid ID', 400);
        }

        // busca depoimento
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // valida se o depoimento existe
        if ( !$obTestimony instanceof EntityTestimony) {
            throw new Exception("Item not found", 404);
        }

        // Retorna os detalhes do item
        return [
            'id' => (int) $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->created_at
        ];
    }

    /**
     * Método responsável por cadastrar um novo depoimento
     *
     * @param Request $request
     */
    public static function setNewTestimony($request) {
        // Post Vars 
        $postVars = $request->getPostVars();

        // valida os campos orbigatórios
        if ( !isset($postVars['nome']) || !isset($postVars['mensagem'])){
            throw new Exception("'nome' and 'mensagem' fields are required", 400);
        }

        // Novo depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->created_at = date('Y-m-d H:i:s');
        $obTestimony->create();

        // retorna os detalhes do item cadastrado
        return [
            'id' => (int) $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->created_at
        ];
    }

    /**
     * Método responsável por cadastrar um novo depoimento
     *
     * @param Request $request
     */
    public static function setEditTestimony($request, $id) {
        // Post Vars 
        $postVars = $request->getPostVars();

        // valida os campos orbigatórios
        if ( !isset($postVars['nome']) || !isset($postVars['mensagem'])){
            throw new Exception("'nome' and 'mensagem' fields are required", 400);
        }

        // Select item no banco
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // valida a instância
        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("Item not found", 404);
        }

        // Atualiza o depoimento
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->update();

        // retorna os detalhes do item atualizado
        return [
            'id' => (int) $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->created_at
        ];
    }

    /**
     * Método responsável por excluir um depoimento
     *
     * @param Request $request
     */
    public static function setDeleteTestimony($request, $id) {
        // Select item no banco
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // valida a instância
        if (!$obTestimony instanceof EntityTestimony) {
            throw new Exception("Item not found", 404);
        }

        // Atualiza o depoimento
        $obTestimony->delete();

        // retorna sucesso da operação
        return [
            'success' => true
        ];
    }
}