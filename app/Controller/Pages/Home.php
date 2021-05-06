<?php

namespace App\Controller\Pages;

use App\Utils\View;

use App\Model\Entity\Organization;

class Home extends Page
{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getHome()
    {

        /**
         * Nova instanção de Organization
         */
        $obOrganization = new Organization;

                // View da Home 
        $content =  View::render('pages/home', [
            'name' => $obOrganization->name,
        ]);

        // Retorna a view da página
        return parent::getPage('Home - WDEV', $content);
    }

}