<?php
require 'util.php';
require 'database.php';

class App
{
   var $db = null;
   var $dir = null;

   private static $_instance = null;
   
   private function __construct()
   {
      session_start();
      $this->dir = getcwd();

      $this->_connect_mysql();
   }

   private function _connect_mysql()
   {
      $mysql_config = config()['mysql'];
      $this->db = new Database($mysql_config['server'], $mysql_config['username'], $mysql_config['password'], $mysql_config['db']);
   }
   
   public static function getInstance()
   {
     if (self::$_instance == null)
     {
       self::$_instance = new App();
     }
  
     return self::$_instance;
   }

   public function render($filename, $vars = null) 
   {
      if (is_array($vars) && !empty($vars)) 
      {
        extract($vars);
      }
      ob_start();
      include $this->dir . '/views/' . $filename;
      return ob_get_clean();
   }
}
