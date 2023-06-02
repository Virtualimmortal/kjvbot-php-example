<?php
//phpinfo();
require '../includes/app.php';
require '../includes/classes/bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();
$day_night_mode = get_var('day_night_mode', false);
$night_mode = ((!$_SESSION['day_night_mode']) || ($_SESSION['day_night_mode'] == 'night') || ($day_night_mode == 'night'));
if ($day_night_mode == 'day') $night_mode = false;

$search_string = get_var('s');
// Also search for a specific verse id if one is provided
//$vid = get_var('vid');
//$search_string = implode(';', array_filter(array_merge(explode(';', $search_string), array($vid))));

$search_results = $bible->search($search_string);
debug($search_string);

$search_page = (empty($search_string) && empty($search_results)) ? 'search.php' : 'search_results.php';
$view = get_var('p', $search_page);
$page = array(
   '#view' => 'pages/' . $view,
);

$meta = $bible->getMeta();
if (empty($meta))
   $meta = array(
      'title' => 'Bible Search',
      'description' => 'Study to shew thyself approved unto God 🔎',
      'type' => 'website',
      'url' => config('base_url'),
      'image' => config('base_url').'/images/eh5zp6hvg__1280.jpg',
   );

render('components/head.php', array('meta' => $meta));
?>


<body class="<?= ($night_mode) ? 'nightMode' : '' ?>">

<? /*
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0&appId=242567873466625&autoLogAppEvents=1" nonce="DBtXoems"></script>
*/ ?>

   <? render($page['#view'], array('search_results' => $search_results, 'search_string' => $search_string)); ?>

   <? if (config('request_donations')) 
         render('components/footer.php'); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/js/materialize.min.js" type="text/javascript"></script>
   <script src="/js/overlay.js"></script>
   <script src="/js/dialogs/share.js"></script>
   <script src="/js/dialogs/save.js"></script>
   <script src="/js/dialogs/open.js"></script>
   <script src="/js/init.js"></script>

   <? //render('components/google_analytics.php'); ?>

   <?php
   if (!$_SESSION['under_construction_notification'] && config('under_construction'))
   {
      $_SESSION['under_construction_notification'] = true;
      render('components/under_construction.php');
   }
   ?>
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

</body>

</html>
