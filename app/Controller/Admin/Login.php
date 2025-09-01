<?php

namespace App\Controller\Admin;

use App\Core\AuthManager;
use Exception;
use App\Core\View;
use App\Utils\Alert;
use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User;

class Login extends Page
{
  /**
   * Get login page
   * @param Request $request
   * @return string
   */
  public static function getLogin(Request $request, string $message = '')
  {
    $vars = Alert::getFlash();

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
    try {
      $postVars = $request->getPostVars();
      $email    = $postVars['email'] ?? '';
      $password = $postVars['password'] ?? '';

      $user = User::getUserByEmail($email);
      if (!$user instanceof User || !password_verify($password, $user->getPassword())) {
        return self::getLogin($request, 'E-mail or password invalid.');
      }

      // Create the login session
      $obAuthManager = new AuthManager;
      $obAuthManager->login($user);
      
      // Redirect to admin home
      return $request->getRouter()->redirect('/admin');
    } catch (\Exception $e) {
      throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
