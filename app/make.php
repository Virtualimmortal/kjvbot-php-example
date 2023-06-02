<?php
require '../includes/app.php';
require 'bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();

$action = get_var('a');
$params = array();

if (function_exists('make_' . $action))
{
   call_user_func_array('make_' . $action, $params);
}

switch($action)
{
   case 'tocObject':
      makeTocObject();
      break;

   default:
      echo 'Nothing to do';
}


function makeTocObject()
{
   $mysqli = App::getInstance()->db;
   //$param = "%{$facet}%";
   $sqlquery = "SELECT b, c, v FROM kjv.t_kjv";
   $stmt = $mysqli->stmt_init();
   $stmt->prepare($sqlquery);
   //$stmt->bind_param("s", $param);
   $stmt->execute();
   $result = $stmt->get_result();
   
   if ($result->num_rows >= 1) 
   {
      $vids = $result->fetch_all();
      $toc = array();
      foreach ($vids as $vid)
      {
         $book = $vid[0];
         $chapter = $vid[1];
         $verse = $vid[2];

         $toc[$book][$chapter][] = $verse;
      }
      $fp = fopen('js/toc.json', 'w');
      fwrite($fp, json_encode($toc));
      fclose($fp);
      $utils = new ScriptureUtils();
      debug($utils->booksFullnames);
      $fp = fopen('js/books.json', 'w');
      fwrite($fp, json_encode($utils->booksFullnames));
      fclose($fp);

      echo 'Finished!';
   }   
}
