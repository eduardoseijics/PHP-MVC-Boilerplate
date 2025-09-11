<?php

namespace App\Controller\Admin;

use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Service\AuthService;
use App\Session\Admin\AuthManager;

class Login extends Page
{
  /**
   * Get login page
   * @param Request $request
   * @return string
   */
  public static function getLogin(Request $request): string
  {
    $vars = [
      'alert' => Alert::getAlert()
    ];

    $content = View::render('admin/login', $vars);

    return parent::getPage($content, 'Login | Admin');
  }

  /**
   * Set login action
   * @param Request $request
   * @return mixed
   */
  public static function setLogin(Request $request): mixed
  {
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $password = $postVars['password'] ?? '';

    // Use AuthService to validate user credentials
    $authService = new AuthService;
    $user = $authService->validateCredentials($email, $password);

    if (!$user) {
      Alert::error('Email or password invalid.');
      return $request->getRouter()->redirect('/admin/login');
    }

    // Login the user
    $auth = new AuthManager();
    $auth->login($user);

    return $request->getRouter()->redirect('/admin');
  }

  /**
   * Set logout action
   * @param Request $request
   * @return mixed
   */
  public static function setLogout(Request $request): mixed
  {
    // Destroy the login session
    $obAuthManager = new AuthManager;
    $obAuthManager->logout();

    // Redirect to login
    return $request->getRouter()->redirect('/admin/login');
  }
}
