<?php
$bible = Bible::getInstance();
render('pages/search_results/header.php', array('search_string' => $search_string));
?>

<div class="section no-pad-bot" id="index-banner">

<div class="container">
   <!--
   <div class="row center">
      <h3 class="header col s12 light"> to shew thyself approved unto God,</h3>
   </div>
   -->
   <div class="row center">
   </div>
   <div class="row center">
      <h1 class="center">Bible Search</h1>
      <div class="primary-search-field input-field s6 s12 black-text">
         <!--<i class="grey-text material-icons prefix">search</i>-->

         <form action="search.php" method="get">

            <input name="s" id="index-search-field" value="<?= $search_string; ?>" type="text" class="scripture-search grey-text" placeholder="Search Scriptures" >
         
         </form>

      </div>
      <!--<h5 class="header col s12 light">a workman that needeth not to be ashamed, rightly dividing the word of truth.</h5>-->
   </div>
   <div class="row center">

      <a href="#" id="index-search-btn" class="btn btn-large waves-effect waves-light grey lighten-4 grey-text text-darken-1"><i class="material-icons">search</i></a>
   </div>

</div>



</div>
<div class="container">
   <div class="fb-like left" data-href="http://kjvbot.com" data-colorscheme="dark" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="true"></div>
</div>
<script>
(function($) {
var $field = $('#index-search-field');
$field.closest('.primary-search-field').addClass('focus')
$field.focus().select();
})(jQuery);
</script>
