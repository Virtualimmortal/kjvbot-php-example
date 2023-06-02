<?php
require '../includes/app.php';
require 'bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();

$day_night_mode = get_var('day_night_mode', false);
$night_mode = ((!$_SESSION['day_night_mode']) || ($_SESSION['day_night_mode'] == 'night') || ($day_night_mode == 'night'));
$bid = get_var('bid');
$search_string = get_var('s');
$search_results = $bible->search($search_string);
//debug($search_results);


$meta = $bible->getMeta();
if (empty($meta))
   $meta = array(
      'title' => 'Editing Results',
      'description' => 'Study to shew thyself approved unto God 🔎',
      'type' => 'website',
      'url' => config('base_url'),
      'image' => config('base_url').'/images/eh5zp6hvg__1280.jpg',
   );
render('components/head.php', array('meta' => $meta));
?>


<body class="<?= ($night_mode) ? 'nightMode grey darken-4 grey-text text-lighten-3' : '' ?>">

   <? render('/pages/edit.php', array('bid' => $bid, 'search_string' => $search_string, 'search_results' => $search_results)); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/js/materialize.min.js" type="text/javascript"></script>
   <script src="/js/overlay.js" type="text/javascript"></script>
   <script src="/js/init.js" type="text/javascript"></script>

   <? render('components/google_analytics.php'); ?>

   <?php
   if (!isset($_SESSION['notice']))
   {
      $_SESSION['notice'] = true;
      render('components/under_construction.php');
   }
   ?>
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

   <? //render('components/footer.php'); ?>

</body>

</html>
