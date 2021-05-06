<?php

namespace App\Controller\Pages;

use App\Utils\View;

use App\Model\Entity\Organization;

class About extends Page
{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */
    public static function getAbout()
    {

        /**
         * Nova instanção de Organization
         */
        $obOrganization = new Organization;

                // View da Home 
        $content =  View::render('pages/about', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->description,
            'site' => $obOrganization->site
        ]);

        // Retorna a view da página
        return parent::getPage('ABOUT - WDEV', $content);
    }

}