<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony {

    /**
     * ID do depoimento
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário que fez o depoimento
     * @var string
     */
    public $nome;

    /**
     * Mensagem do depoimento
     * @var string
     */
    public $mensagem;

    /**
     * Data de publicação do depoimento
     *
     * @var string
     */
    public $data;

    /**
     * Método responsável por retornar depoimentos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('testimonies'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um depoimento com base no seu ID
     * $param integer $id
     * @return Testimony
     */
    public static function getTestimonyById($id)
    {
        return self::getTestimonies("id = {$id}")->fetchObject(self::class);
    }

    /**
     * Método responsável por cadastrar a instância atual no Banco de dados
     *
     * @return boolean
     */
    public function create()
    {
        //Define a data (se não for definido no BD)
        // $this->created_at = date('Y-m-d H:i:s');

        //Insere o depoimento no BD
        $this->id = (new Database('testimonies'))
                    ->insert([
                        'nome' => $this->nome,
                        'mensagem' => $this->mensagem,
                        // 'created_at' => $this->created_at
                    ]);

        // SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar a instância atual no Banco de dados
     *
     * @return boolean
     */
    public function update()
    {

        //Atualiza o depoimento no BD
        return (new Database('testimonies'))
                    ->update('id = '.$this->id, [
                        'nome' => $this->nome,
                        'mensagem' => $this->mensagem,
                    ]);

        // SUCESSO
        return true;
    }

    /**
     * Método responsável por excluir a instância atual no Banco de dados
     *
     * @return boolean
     */
    public function delete()
    {

        //Exclui o depoimento no BD
        return (new Database('testimonies'))
                    ->delete('id = '.$this->id);

        // SUCESSO
        return true;
    }

}