<?php

class Errorx extends Controller
{

  function __construct()
  {
    parent::__construct();
    error_log("Errores::construct-> inicio de Errores");
  }

  function render()
  {
    $this->view->render('errores/index');
  }
}
