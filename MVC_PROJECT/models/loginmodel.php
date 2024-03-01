<?php

require_once 'models/usermodel.php';

class LoginModel extends Model
{

  function __construct()
  {
    parent::__construct();
    error_log("LoginModel::construct-> inicio de LoginModel");
  }

  function login($username, $password)
  {

    try {

      $query = $this->prepare('SELECT * FROM users WHERE username = :username');
      $query->execute(['username' => $username]);

      if ($query->rowCount() === 1) {

        $user = new UserModel();
        $user->from($query->fetch(PDO::FETCH_ASSOC));

        if (password_verify($password, $user->getPassword())) {
          error_log('loginModel::login => Los passwords son iguales');
          return $user;
        } else {
          error_log('loginModel::login => Los passwords no son iguales');
          return NULL;
        }
      }
    } catch (PDOException $e) {
      error_log('LoginModel::login => Error al ingresa a la DB ' . $e);
      return NULL;
    }
  }
}
