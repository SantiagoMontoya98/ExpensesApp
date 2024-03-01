<?php

require_once 'classes/session.php';
require_once 'models/usermodel.php';

class SessionController extends Controller
{

  private $userSession;
  private $userName;
  private $userId;

  private $session;
  private $sites;

  private $user;

  function __construct()
  {
    parent::__construct();
    $this->init();
  }

  function init()
  {
    $this->session = new Session();

    $json = $this->getJSONFileConfig();

    $this->sites = $json['sites'];
    $this->defaultSites = $json['default-sites'];

    $this->validateSession();
  }

  private function getJSONFileConfig()
  {
    $string = file_get_contents('config/access.json');
    $json = json_decode($string, true);

    return $json;
  }

  public function validateSession()
  {
    error_log('SESSIONCONTROLLER::validateSession ->');

    if ($this->existsSession()) {
      $role = $this->getUserSessionData()->getRole();

      if ($this->isPublic()) {
        $this->redirectDefaultSiteByRole($role);
      } else {

        if (!$this->isAutorizhed($role))
          $this->redirectDefaultSiteByRole($role);
      }
    } else {

      if (!$this->isPublic())
        header("Location: " . constant('URL') . '');
    }
  }

  function existsSession()
  {
    if (!$this->session->exists()) return false;
    if (!$this->session->getCurrentUser()) return false;

    $userid = $this->session->getCurrentUser();

    if ($userid) return true;

    return false;
  }

  function getUserSessionData()
  {
    $id = $this->session->getCurrentUser();
    $this->user = new UserModel();
    $this->user->get($id);

    error_log('SESSIONCONTROLLER::getUserSessionData -> ' . $this->user->getUserName());

    return $this->user;
  }

  function isPublic()
  {
    $currentURL = $this->getCurrentPage();
    $currentURL = preg_replace("/\?.*/", "", $currentURL);

    for ($i = 0; $i < sizeof($this->sites); $i++)
      if ($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['access'] === 'public') return true;

    return false;
  }

  function getCurrentPage()
  {
    $actualLink = trim("$_SERVER[REQUEST_URI]");
    $url = explode('/', $actualLink);
    error_log('SESSIONCONTROLLER::getCurrentPage -> ' . $url[2]);
    return $url[2];
  }

  private function redirectDefaultSiteByRole($role)
  {
    $url = '';

    for ($i = 0; $i < sizeof($this->sites); $i++) {

      if ($this->sites[$i]['role'] == $role) {
        $url = $this->sites[$i]['site'];
        break;
      }
    }

    header("Location: {$url}");
  }

  private function isAutorizhed($role)
  {
    $currentURL = $this->getCurrentPage();
    $currentURL = preg_replace("/\?.*/", "", $currentURL);

    for ($i = 0; $i < sizeof($this->sites); $i++)
      if ($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['role'] === $role) return true;

    return false;
  }

  function initialize($user)
  {
    $this->session->setCurrentUser($user->getId());
    $this->autorizheAccess($user->getRole());
  }

  function autorizheAccess($role)
  {

    switch ($role) {

      case 'user':

        $this->redirect($this->defaultSites['user'], []);
        break;

      case 'admin':

        $this->redirect($this->defaultSites['admin'], []);
        break;
    }
  }

  function logout()
  {
    $this->session->closeSession();
  }
}
