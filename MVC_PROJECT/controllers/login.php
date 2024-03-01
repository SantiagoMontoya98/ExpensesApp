<?php

class Login extends SessionController
{

  function __construct()
  {
    parent::__construct();
    error_log("Login::construct-> inicio de Login");
  }

  function render()
  {
    error_log("Login::render-> carga el index de login");
    $this->view->render('login/index');
  }

  function authenticate()
  {
    if ($this->existsPOST(['username', 'password'])) {

      $username = $this->getPost('username');
      $password = $this->getPost('password');

      if ($username === '' || empty($username) || $password === '' || empty($password)) {

        $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_EMPTY]);
        return false;
      }

      $user = $this->model->login($username, $password);

      if ($user)
        $this->initialize($user);
      else
        $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE_DATA]);
    } else
      $this->redirect('', ['error' => ErrorMessages::ERROR_LOGIN_AUTHENTICATE]);
  }
}
