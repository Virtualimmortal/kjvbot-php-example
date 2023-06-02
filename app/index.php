<?php
require '../app/includes/app.php';
require '../includes/classes/bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();

$force_night = get_var('night_mode', false);
$night_mode = (($_SESSION['night_mode'] == 'true') || $force_night);

$search_string = get_var('s');
$search_results = $bible->search($search_string);

$search_page = (empty($search_results)) ? 'search' : 'search_results';
$view = get_var('p', $search_page);
$page = array(
   '#view' => 'pages/' . $view . '.php',
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
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

   <? render($page['#view'], array('search_results' => $search_results, 'page' => $page)); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/bible/js/materialize.min.js" type="text/javascript"></script>
   <script src="/bible/js/overlay.js"></script>
   <script src="/bible/js/init.js"></script>
   <script src="/bible/js/mediabrain.js"></script>

   <? render('components/google_analytics.php'); ?>
</body>

</html>
