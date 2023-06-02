<?php
require '../includes/app.php';
require 'bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();

$day_night_mode = get_var('day_night_mode', false);
$night_mode = ((!$_SESSION['day_night_mode']) || ($_SESSION['day_night_mode'] == 'night') || ($day_night_mode == 'night'));

$search_string = get_var('s');
// Also search for a specific verse id if one is provided
//$vid = get_var('vid');
//$search_string = implode(';', array_filter(array_merge(explode(';', $search_string), array($vid))));

$search_results = $bible->search($search_string);
//debug($search_results);

$search_page = (empty($search_string) && empty($search_results)) ? 'search.php' : 'search_results.php';
$view = get_var('page', $search_page);
$page = array(
   '#view' => 'pages/' . $view,
);

$meta = $bible->getMeta();
if (empty($meta))
   $meta = array(
      'title' => 'Bible Search',
      'description' => 'Study to shew thyself approved unto God 🔎',
      'type' => 'website',
      'url' => 'https://mybiblebot.net/',
      'image' => 'https://www.mybiblebot.net/images/eh5zp6hvg__1280.jpg',
   );

render('components/head.php', array('meta' => $meta));
?>


<body class="<?= ($night_mode) ? 'nightMode grey-text text-lighten-3' : '' ?>">


   <? render('pages/thank-you.php'); ?>


   <? render($page['#view'], array('search_results' => $search_results, 'search_string' => $search_string)); ?>

   <? //render('components/footer.php'); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/js/materialize.min.js" type="text/javascript"></script>
   <script src="/js/overlay.js"></script>
   <script src="/js/init.js"></script>
   <script src="/js/mediabrain.js"></script>

   <? render('components/google_analytics.php'); ?>
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

   <!--
   <footer class="page-footer fixed">
      <div class="container">
         <div class="row">
            <div class="col center">
               <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                  <input type="hidden" name="cmd" value="_s-xclick" />
                  <input type="hidden" name="hosted_button_id" value="NEV9SM2N3NLAW" />
                  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
               </form>
            </div>
         </div>
   </footer>
   -->
</body>

</html>
