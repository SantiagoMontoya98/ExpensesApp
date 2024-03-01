<?php

require_once 'models/usermodel.php';

class SignUp extends SessionController
{

  function __construct()
  {
    parent::__construct();
  }

  function render()
  {
    error_log("SignUp::render-> carga la vista de signup");
    $this->view->render('login/signup', []);
  }

  function newUser()
  {

    if ($this->existsPOST(['username', 'password'])) {

      $username = $this->getPost('username');
      $password = $this->getPost('password');

      if ($username === '' || empty($username) || $password === '' || empty($password)) {

        $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EMPTY]);
        return false;
      }

      $user = new UserModel();
      $user->setUserName($username);
      $user->setPassword($password);
      $user->setRole('user');

      if ($user->exists($username)) {

        $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER_EXISTS]);
      } else if ($user->save()) {
        $this->redirect('', ['success' => SuccessMessages::SUCCESS_SIGNUP_NEWUSER]);
      } else {
        $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
      }
    } else {
      $this->redirect('signup', ['error' => ErrorMessages::ERROR_SIGNUP_NEWUSER]);
    }
  }
}
