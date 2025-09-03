<?php

namespace App\Model\Entity;

use PDO;
use App\Core\Database;
use PDOStatement;

class User
{

  /**
   * User id
   * @var int
   */
  private $id;

  /**
   * User name
   * @var string
   */
  private $name;

  /**
   * User e-mail
   * @var string
   */
  private $email;

  /**
   * User password
   * @var string
   */
  private $password;

  private string $type; 

  /**
   * Get the user ID
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Get the user password
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * Get the user e-mail
   * @return string
   */
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * Get the user name
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Get the user type
   * @return string
   */
  public function getType(): string
  {
    return $this->type;
  }

  /**
   * Set the user password
   * @return self
   */
  public function setPassword($password): self
  {
    $this->password = $password;
    return $this;
  }

  /**
   * Set the user e-mail
   * @return self
   */
  public function setEmail($email): self
  {
    $this->email = $email;
    return $this;
  }

  /**
   * Seta o nome do usuário
   * @return self
   */
  public function setName($name): self
  {
    $this->name = $name;
    return $this;
  }

  /**
   * Set the user type
   * @return self
   */
  public function setType($type): self
  {
    $this->type = $type;
    return $this;
  }

  /**
   * Método responsável por obter os dados de um user
   * @param int $id
   * @param string $campos
   * @return User
   */
  public static function getUserById(int $id, $fields = '*')
  {
    return self::getUserByQuery('id = "' . $id . '"', null, null, $fields)->fetchObject(self::class);
  }

  /**
   * Find an user by email
   * @param  string $email
   * @return User
   */
  public static function findByEmail(string $email)
  {
    return (new Database('user'))->select("email = '$email' ")->fetchObject(self::class);
  }


  /**
   * Get an user by query
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getUserByQuery($where = null, $params = [], $order = null, $limit = null, $fields = '*'): PDOStatement
  {
    return (new Database('user'))->select($where, $params, $fields, $order, $limit);
  }

  /**
   * Método responsável por cadastrar um cliente
   * @method cadastrar
   * @param  mixed     $dadosCliente    Instancia de Cliente ou array de dados
   * @return integer   ID do cliente criado
   */
  public static function create(array $dadosUsuario = [])
  {

    $obDatabaseUsuario = new Database('user');
    $idUsuario = $obDatabaseUsuario->insert($dadosUsuario);

    return $idUsuario;
  }

  /**
   * Responsavel por excluir o usuario do banco
   * @return boolean
   */
  public function delete()
  {
    return (new Database('user'))->delete("id = {$this->id}");
  }

  public static function cifrarSenha($senha)
  {
    return password_hash($senha, PASSWORD_DEFAULT);
  }

  /**
   * Atualiza os dados do banco com os dados da instância atual
   * @return bool
   */
  public function update()
  {
    return (new Database('user'))->update("id = " . $this->id, [
      'nome'  => $this->name,
      'email' => $this->email
    ]);
  }
}
