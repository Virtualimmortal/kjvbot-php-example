<?php
require '../includes/app.php';
require '../app/includes/classes/bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();

$force_night = get_var('night_mode', false);
$night_mode = (($_SESSION['night_mode'] == 'true') || $force_night);

$search_string = get_var('v');

$search_results = $bible->search($search_string);
//debug($search_results);

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


<body class="<?= ($night_mode) ? 'nightMode grey darken-4 grey-text text-lighten-3' : '' ?>">
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

   <? render('pages/search_results.php', array('search_results' => $search_results, 'search_string' => $search_string)); ?>

   <? //render('components/footer.php'); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/js/materialize.min.js" type="text/javascript"></script>
   <script src="/js/overlay.js"></script>
   <script src="/js/init.js"></script>
   <script src="/js/mediabrain.js"></script>

   <? render('components/google_analytics.php'); ?>

</body>

</html>
