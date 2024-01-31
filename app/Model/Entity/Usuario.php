<?php

namespace App\Model\Entity;

use App\Core\Database;
use App\Model\interfaces\InterfaceModel;

class Usuario implements InterfaceModel{

  /**
   * ID do usuário
   * 
   * @var int
   */
  private $id;

  /**
   * Nome do usuário
   * 
   * @var string
   */
  private $nome;

  /**
   * E-mail do usuário
   * 
   * @var string
   */
  private $email;

  /**
   * Senha do usuário
   * 
   * @var string
   */
  private $senha;

  /**
   * Telefone do usuário
   * 
   * @var string
   */
  private $telefone;

  /**
   * Tipo do usuario
   * 
   * @var enum(coordernador, professor, aluno)
   */
  private $tipo;


  /**
   * Retorna o ID do usuário
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Retorna o tipo do usuário
   * @return string
   */
  public function getTipo() {
    return $this->tipo;
  }

  /**
   * Retorna a senha do usuário
   * @return string
   */
  public function getSenha() {
    return $this->senha;
  }

  /**
   * Retorna o e-mail do usuário
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Retorna o telefone do usuário
   * @return string
   */
  public function getTelefone() {
    return $this->telefone;
  }

  /**
   * Retorna o nome do usuário
   * @return string
   */
  public function getNome() {
    return $this->nome;
  }

  /**
   * Seta o tipo do usuário
   * @return self
   */
  public function setTipo($tipo) {
    $this->tipo = $tipo;
    return $this;
  }

  /**
   * Seta a senha do usuário
   * @return self
   */
  public function setSenha($senha) {
    $this->senha = $senha;
    return $this;
  }

  /**
   * Retorna o e-mail do usuário
   * @return self
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  /**
   * Seta o telefone do usuário
   * @return self
   */
  public function setTelefone($telefone) {
    $this->telefone = $telefone;
    return $this;
  }

  /**
   * Seta o nome do usuário
   * @return self
   */
  public function setNome($nome) {
    $this->nome = $nome;
    return $this;
  }

  /**
   * Responsavel por retornar as noticias do banco de dados
   * @param int $limit
   * @return PDOStatement
   */
  public static function getusuarios($where = null, $order = null, $limit = 10, $fields = '*') {
    
    return (new Database('usuarios'))->select($where, $order, $limit, $fields);
  }

  /**
   * Método responsável por obter os dados de um usuarios
   * @param int $id
   * @param string $campos
   * @return Usuario
   */
  public static function getUsuarioPorId($id, $campos = '*') {
    return self::getUsuariosPorQuery('id = "'.$id.'"', null, null, $campos)->fetchObject(self::class);
  }

  /**
   * Obter usuário via e-mail
   *
   * @param  string $email
   * @return Usuario
   */
  public static function getUsuarioPorEmail($email) {
    return (new Database('usuarios'))->select("email = '$email' ")->fetchObject(self::class);
  }


  /**
   * Método responsável por retornar os usuarios de acordo com os parâmetros
   * @param  string $where
   * @param  string $order
   * @param  string $limit
   * @param  string $fields
   * @return PDOStatement
   */
  public static function getUsuariosPorQuery($where = null, $order = null, $limit = null, $fields = '*') {
    return (new Database('usuarios'))->select($where,$order,$limit,$fields);
  }

  /**
   * Método responsável por cadastrar um cliente
   * @method cadastrar
   * @param  mixed     $dadosCliente    Instancia de Cliente ou array de dados
   * @return integer   ID do cliente criado
   */
  public static function cadastrar(array $dadosUsuario = []){

    $obDatabaseUsuario = new Database('usuarios');
    $idUsuario = $obDatabaseUsuario->insert($dadosUsuario);

    return $idUsuario;
  }

  /**
   * Responsavel por excluir o usuario do banco
   * @return boolean
   */
  public function delete() {
    return (new Database('usuarios'))->delete("id = {$this->id}");
  }

  public static function cifrarSenha($senha) {    
    return password_hash($senha, PASSWORD_DEFAULT);
  }

  /**
   * Atualiza os dados do banco com os dados da instância atual
   * @return bool
   */
  public function update() {
    return (new Database('usuarios'))->update("id = ".$this->id, [
      'nome'  => $this->nome,
      'email' => $this->email,
      'tipo'  => $this->tipo
    ]);
  }
}