<?php
namespace App\Controller\Api;

class Api {

    /**
     * Método responsável por retornar os detalhes da API
     *
     * @param Request $request
     * @return array
     */
    public static function getDetails($request)
    {
        return [
            'name' => 'API Matrícula Online | IM',
            'version' => 'v1.0.0',
            'author' => 'Thiago F. da Rosa',
            'email' => 'thyagogoth@gmail.com'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     *
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    protected static function getPagination($request, $obPagination){
        //Query Params
        $queryParams = $request->getQueryParams();

        // Paginas
        $pages = $obPagination->getPages();

        // retorno
        return [
            'current' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'total' => !empty($pages) ? count($pages) : 1
        ];
    }

}