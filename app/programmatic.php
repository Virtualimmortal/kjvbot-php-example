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
      'title' => 'Program a Query',
      'description' => 'Study to shew thyself approved unto God 🔎',
      'type' => 'website',
      'url' => config('base_url'),
      'image' => config('base_url').'/images/eh5zp6hvg__1280.jpg',
   );
render('components/head.php', array('meta' => $meta));
?>

<script src="/js/blockly/storage.js"></script>   
<script src="/js/blockly/blockly_compressed.js" type="text/javascript"></script>
<script src="/js/blockly/blocks_compressed.js" type="text/javascript"></script>
<script src="/js/blockly/javascript_compressed.js"></script>
<script src="/js/blockly/python_compressed.js"></script>
<script src="/js/blockly/php_compressed.js"></script>
<script src="/js/blockly/lua_compressed.js"></script>
<script src="/js/blockly/dart_compressed.js"></script>
<script src="/js/blockly/code/code.js"></script>   

<body class="<?= ($night_mode) ? 'nightMode grey darken-4 grey-text text-lighten-3' : '' ?> programmatic">

   <? render('/pages/programmatic.php', array('bid' => $bid, 'search_string' => $search_string, 'search_results' => $search_results)); ?>



   <? render('components/google_analytics.php'); ?>

   <?php
   if (!isset($_SESSION['notice']))
   {
      $_SESSION['notice'] = true;
      render('components/under_construction.php');
   }
   ?>

   <div id="blocklyModal" class="modal">
      <div class="modal-content">
         
      </div>
      <div class="modal-footer">
         <a href="#!" class="modal-close waves-effect waves-light btn-flat">Ok</a>
      </div>
   </div>
   
   <div id="loadingIndicator">
      <? render('components/loading_indicator.php'); ?>
   </div>

   <? render('components/footer.php'); ?>

   <!--  Scripts-->
   <!--JavaScript at end of body for optimized loading-->
   <script src="/js/materialize.min.js" type="text/javascript"></script>
   <script src="/js/init.js" type="text/javascript"></script>

</body>

</html>
