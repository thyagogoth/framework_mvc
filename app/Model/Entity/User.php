<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class User {

	/**
	 * ID do usuário
	 *
	 * @var integer
	 */
	public $id;

	/**
	 * Nome do usuario
	 *
	 * @var string
	 */
	public $nome;

	/**
	 * E-mail do usuário
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Senha do usuário
	 *
	 * @var string
	 */
	public $senha;

	 /**
     * Método responsável por retornar usuários
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('users'))->select($where, $order, $limit, $fields);
    }

	/**
     * Método responsável por retornar um usuário com base no seu ID
     * $param integer $id
     * @return Testimony
     */
    public static function getUserById($id)
    {
        return self::getUsers("id = {$id}")->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base em seu e-mail
     *
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email)
    {
        return self::getUsers("email = '{$email}'")->fetchObject(self::class);
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

        //Insere o usuário no BD
        $this->id = (new Database('users'))
                    ->insert([
                        'nome' => $this->nome,
                        'email' => $this->email,
						'senha' => $this->senha,
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

        //Atualiza o usuário no BD
        return (new Database('users'))
                    ->update('id = '.$this->id, [
                        'nome' => $this->nome,
                        'email' => $this->email,
						'senha' => $this->senha,
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

        //Exclui o usuário no BD
        return (new Database('users'))
                    ->delete('id = '.$this->id);

        // SUCESSO
        return true;
    }
}