<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Controller\Alert;
use App\Model\Entity\Usuario;
use App\Controller\Admin\Page;
use App\Session\Admin\Login as AdminLogin;

class Login extends Page{

  public static function getPaginaLogin($request, $message = '') {
    $vars = ['status' => !empty($message) ? Alert::getError($message) : ''];
    
    return View::render('admin/login', $vars);
  }
  
  /**
   * Definir login do usuário 
   *
   * @param  Request $request
   * @return
   */
  public static function setLogin($request) {
    $postVars = $request->getPostVars();
    $email    = $postVars['email'] ?? '';
    $password = $postVars['senha'] ?? '';

    // Busca usuário pelo e-mail
    $user = Usuario::getUsuarioPorEmail($email);
    
    // Autenticação email e senha de usuário
    if (!$user instanceof Usuario || !password_verify($password, $user->getSenha())) {
      return self::getPaginaLogin($request, 'Email ou senha inválidos');
    }

    // Cria sessão de login
    AdminLogin::login($user);
    // Redireciona o usuário para a home do admin
    $request->getRouter()->redirect('/admin');
  }
  
  /**
   * Deslogar usuário
   *
   * @param  Request $request
   * @return void
   */
  public static function setLogout($request) {
    AdminLogin::logout();

    $request->getRouter()->redirect('/admin/login');
  }

}