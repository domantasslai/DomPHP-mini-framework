<?php

  /*
  * App Core Classe
  * Creates URL & loads core controller
  * URL FORMAT - /controller/method/params
  */

  class Core{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
      // print_r($this->getUrl());

      $url = $this->getUrl();

      // Look in Controllers for first array_count_value
      if (file_exists('../app/controllers/' .ucwords($url[0]). '.php')) {
        // IF exists, set as controllers
        $this->currentController = ucwords($url[0]);
        // Unset 0 index
        unset($url[0]);
      }

      // Require the controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // Instatiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of URL
      if (isset($url[1])) {
        // check to see if methon exist in controllers
        if (method_exists($this->currentController, $url[1])) {
          $this->currentMethod = $url[1];
          // Unset 1 index
          unset($url[1]);
        }
      }

      // Get params
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      call_user_func_array([$this->currentController,
      $this->currentMethod], $this->params);
    }

    public function getUrl(){
      if (isset($_GET['url'])) {
        // pasalina / is URL
        $url = rtrim($_GET['url'], '/');
        // pasalina URL nebudingas reiksmes
        $url = filter_var($url, FILTER_SANITIZE_URL);
        /*
          * atskiria viska tarp / reiksmiu
          * pvz /post/edit bus
          * post edit
        */
        $url = explode('/', $url);

        return $url;
      }
    }
  }
