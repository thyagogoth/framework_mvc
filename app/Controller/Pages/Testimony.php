<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{

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
            $itens .=  View::render('pages/testimony/item', [
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'created_at' => date('d/m/Y H:i', strtotime($obTestimony->created_at))
            ]);
        }
         
        return $itens;
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * $param Request $request
     * @return string
     */
    public static function getTestimonies($request)
    {
        // View de depoimentos
        $content =  View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination)
        ]);

        // Retorna a view da página
        return parent::getPage('Depoimentos - WDEV', $content);
    }

    /**
     * Método responsável por cadastrar um depoimento
     *
     * @param Request $request
     * @return string
     */
    public static function insertTestimony($request) 
    {
        // Dados do post
        $postVars = $request->getPostVars();

        // Nova instância de depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];

        $obTestimony->create();
        
        // Retorna a página de listagem de depoimentos
        return self::getTestimonies($request);
    }

}