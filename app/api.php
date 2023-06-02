<?php
require '../includes/app.php';
$app = App::getInstance();
$action = get_var('action', '');

if (function_exists('api_' . $action))
{
   $data = get_var('data', array());
   call_user_func_array('api_' . $action, array($data));
}


/*
*  API Search
*/
function api_search($params)
{
   require_once '../includes/classes/bible.php';
   $bible = Bible::getInstance();
   $search_results = $bible->search($params);
   header('Content-Type: application/json');
   echo json_encode(array(
      'status' => 'Ok',
      'search_results' => $search_results,
   ));
}

/*
*  Toggle Night Mode
*/
function api_toggle_night_mode($params)
{
   if (isset($_GET['data']['day_night_mode']))
   {
      $_SESSION['day_night_mode'] = $_GET['data']['day_night_mode'];
      header('Content-Type: application/json');
      echo json_encode(array(
         'status' => 'Ok',
         'day_night_mode' => $_SESSION['day_night_mode'],
      ));
   }
}

/*
*  Add Bookmark to $_SESSION
*/
function api_add_bookmark($params)
{
   //debug($params);
   //error_log(print_r($params, 1) . "\n\n", 3, '/var/log/php-fpm/php_error.log');

   $_SESSION['bookmarks'][] = $params['key'];

   $bookmarks = array();
   foreach ($_SESSION['bookmarks'] as $bookmark)
   {
      $bookmarks[] = render('components/bookmark_link.php', array('bookmark' => array('reference' => $bookmark)), 1);
   }

   header('Content-Type: application/json');
   echo json_encode(array(
      'status' => 'Ok',
      'bookmarks' => $bookmarks,
   ));
}


/*
*  Clear all bookmarks from $_SESSION
*/
function api_clear_all_bookmarks($params)
{
   //debug($params);
   //error_log(print_r($params, 1) . "\n\n", 3, '/var/log/php-fpm/php_error.log');
   // Clear all bookmarks fromn session
   $_SESSION['bookmarks'] = array();

   header('Content-Type: application/json');
   echo json_encode(array(
      'status' => 'Ok',
      'bookmarks' => $_SESSION['bookmarks'],
   ));
}

function api_get_session_bookmarks($params)
{
   $type = get_var('type', 'json');
   if ($type == 'file')
   {
      header('Content-disposition: attachment; filename=bookmarks.json');
   }
   header('Content-Type: application/json');
   echo json_encode(array(
      'status' => 'Ok',
      'bookmarks' => $_SESSION['bookmarks'],
   ));
}

function api_upload_session_bookmarks($params)
{
   debug($params);
   
   if (isset($params['imports']['bookmarks']))
   {
      if ($params['merge'])
      {
         foreach ($params['imports']['bookmarks'] as $bookmark)
         {
            $_SESSION['bookmarks'][] = $bookmark;
         }
         debug($_SESSION);
      }
      else
      {
         $_SESSION['bookmarks'] = $params['imports']['bookmarks'];
      }
   }

   $bookmarks = array();
   foreach ($_SESSION['bookmarks'] as $bookmark)
   {
      $bookmarks[] = render('components/bookmark_link.php', array('bookmark' => array('reference' => $bookmark)), 1);
   }
   
   echo json_encode(array(
      'status' => 'Ok',
      'bookmarks' => $bookmarks,
   ));
}



/*
*  API Search
*/
function api_bibleProjectYoutubeIds($params)
{
   require_once '../includes/classes/bibleproject.php';
   header('Content-Type: application/json');
   echo json_encode(BibleProject::$youtube_ids);
}

