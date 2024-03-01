<?php

class Session
{

  private $sessionName = '';

  public function __construct()
  {

    if (session_status() == PHP_SESSION_NONE)
      session_start();
  }

  public function setCurrentUser($user)
  {
    $_SESSION[$this->sessionName] = $user;
  }

  public function getCurrentUser()
  {
    return $_SESSION[$this->sessionName];
  }

  public function closeSession()
  {
    unset($_SESSION[$this->sessionName]);
    session_destroy();
  }

  public function exists()
  {
    return isset($_SESSION[$this->sessionName]);
  }
}
