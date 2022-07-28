<?php

class Controller
{
  function Controller()
  {
    //INCLUDE CONFIG
    include_once './system/config/globalConfig.php';
    $this->config = $config;
    $this->parent = $config['site']['parent'];

    //INCLUDE FUNCTION
    include_once './system/function/systemFunction.php';
    include_once './system/function/customFunction.php';

    //PAGES
    $this->pages = [
      'index',
      'generate',
      // 'comment',
      // 'showroom',
      // 'ajaxShowroom'
    ];

    //HANDLE URL
    $this->url = explodeUrl($this->config['site']['path']);
    $this->pageHandle();
  }
  private function pageHandle()
  {
    includeAllFiles('./system/controller/','php');
    if($this->url[1] == '214')
      $this->view = new index214($this->url);
    elseif(in_array($this->url[1], $this->pages))
      $this->view = new $this->url[1]($this->url);
    else
      $this->view = new index($this->url); //default
  }
}
