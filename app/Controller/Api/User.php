<?php
namespace App\Controller\Api;

use App\Model\Entity\User as EntityUser;
use Exception;
use WilliamCosta\DatabaseManager\Pagination;

class User extends Api {

    /**
     * Método responsável por retornar os usuários cadastrados
     *
     * @param Request $request
     * @return array
     */
    public static function getUsers($request)
    {
        return [
            'data' => self::getUserItems($request, $obPagination),
            'pages' => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por obter a renderização dos itens de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getUserItems($request, &$obPagination)
    {
        // Depoimentos
        $itens = [];

        // Quantidade total de registros
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(id) AS qtd')->fetchObject()->qtd;

        // Define a página atual do projeto
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // Instância de Paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, ITEMS_PER_PAGE);

        // Resultados da página
        $results = EntityUser::getUsers(null, 'nome ASC', $obPagination->getLimit());

        // Renderiza o item
        while($obUser = $results->fetchObject(EntityUser::class)){
            // array de usuários
            $itens[] =  [
                'id' => (int) $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ];
        }
         
        // retorna os usuários
        return $itens;
    }

    /**
     * Método responsável por retornar os detalhes de um usuário
     *
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getUser($request, $id) {
        // Valida o id do Item 
        if ( !is_numeric($id) ) {
            throw new Exception('Invalid ID', 400);
        }

        // busca usuário
        $obUser = EntityUser::getUserById($id);

        // valida se o usuário existe
        if ( !$obUser instanceof EntityUser) {
            throw new Exception("Item not found", 404);
        }

        // Retorna os detalhes do usuário
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ];
    }

    /**
     * Método responsável por retornar o usuário atual conectado (JWT)
     *
     * @param Request $request
     * @return array'
     */
    public static function getCurrentUser($request) {
        // Usuário atual
        $obUser = $request->user;
        // Retorna os detalhes do usuário
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuário
     *
     * @param Request $request
     */
    public static function setNewUser($request) {
        // Post Vars 
        $postVars = $request->getPostVars();

        // valida os campos orbigatórios
        if ( !isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['senha'])){
            throw new Exception("'nome', 'email' and 'senha' fields are required", 400);
        }

        //Valida a duplicação de usuários
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if ( $obUserEmail instanceof EntityUser) {
            throw new Exception('Email already exists', 400);
        }

        // Novo usuário
        $obUser = new EntityUser;
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser->create();

        // retorna os detalhes do item cadastrado
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuário
     *
     * @param Request $request
     */
    public static function setEditUser($request, $id) {
        // Post Vars 
        $postVars = $request->getPostVars();

        // valida os campos orbigatórios
        if ( !isset($postVars['nome']) || !isset($postVars['email'])){
            throw new Exception("'nome' and 'email' fields are required", 400);
        }

        // Select item no banco
        $obUser = EntityUser::getUserById($id);

        // valida se o usuário existe
        if (!$obUser instanceof EntityUser && $obUser->id != $obUser->id) {
            throw new Exception("Item not found", 404);
        }

        // Valida a duplicidade de usuários
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if ( $obUserEmail instanceof EntityUser && $obUserEmail->id != $obUser->id) {
            throw new Exception('Email already exists', 400);
        }

        // Atualiza o usuário
        $obUser->nome = $postVars['nome'];
        $obUser->mensagem = $postVars['email'];
        if ( !empty($postVars['senha']) ) {
            $obUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        }
        $obUser->update();

        // retorna os detalhes do item atualizado
        return [
            'id' => (int) $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email,
        ];
    }

    /**
     * Método responsável por excluir um usuário
     *
     * @param Request $request
     */
    public static function setDeleteUser($request, $id) {
        // Select item no banco
        $obUser = EntityUser::getUserById($id);

        // valida a instância
        if (!$obUser instanceof EntityUser) {
            throw new Exception("Item not found", 404);
        }

        // Evita auto-exclusão
        if ( $obUser->id == $request->user->id) {
            throw new Exception("Bad request", 400);
        }

        // exclui o usuário
        $obUser->delete();

        // retorna sucesso da operação
        return [
            'success' => true
        ];
    }

}