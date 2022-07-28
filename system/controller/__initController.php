<?php

class View
{
  function __construct()
  {
    //CONFIG
    include './system/config/globalConfig.php';
    $this->config = $config;

    //DATABASE
    if($this->config['setting']['enable-database'] == true)
    {
      define('DBHOST',$this->config['database']['host']);
      define('DBUSER',$this->config['database']['user']);
      define('DBPASS',$this->config['database']['pass']);
      define('DBNAME',$this->config['database']['db']);

      include './system/core/siteModel.php';
      foreach(glob('./system/model/*.php') as $filename)
      {
        include $filename;
        $pathArray = explode('/', preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename));
        $classname = end($pathArray);
        $this->model[$classname] = new $classname();
      }
    }
  }
  public function handle($function=null,$parameter=null)
  {
    $args = func_get_args();
    array_shift($args);
    return call_user_func_array(array($this->getModel($function), $function), $args);
  }
  private function getModel($function=null)
  {
    foreach($this->model as $key => $value)
    {
      $methods = get_class_methods($key);
      foreach ($methods as $name)
      {
        if($function == $name)
        {
          return $this->model[$key];
          exit;
        }
      }
    }
  }
}
