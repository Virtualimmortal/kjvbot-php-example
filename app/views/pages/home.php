
<div class="container site-title">
   <!--
   <div class="row center">
      <h3 class="header col s12 light"> to shew thyself approved unto God,</h3>
   </div>
   -->
   <div class="row center">
      <!-- <h1 class="center hide-on-small-only">Gas City, Indiana</h1> -->
   </div>

   <div class="row center">

      <div class="primary-search-field input-field s6 s12">

         <!--<i class="grey-text material-icons prefix">search</i>-->
         <form action="search.php" method="get" autocomplete="off">
            <input name="s" id="index-search-field" value="<?= $search_string; ?>" type="text" class="scripture-search left" placeholder="Search Gas City, IN" autocomplete="false">
            <a href="#" id="index-search-btn" class="btn btn-large waves-effect waves-light right"><i class="material-icons">search</i></a>
         </form>

      </div>

      <!--<h5 class="header col s12 light">a workman that needeth not to be ashamed, rightly dividing the word of truth.</h5>-->
   </div>

   <div class="row center">

   </div>

</div>


<script>
(function($) {
var $field = $('#index-search-field');
$field.closest('.primary-search-field').addClass('focus')
$field.focus().select();
})(jQuery);
</script>
