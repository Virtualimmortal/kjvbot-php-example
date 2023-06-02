<!-- Modal Structure -->
<div id="under_construction" class="modal">
   <div class="modal-content">
      <h4>Development Notice</h4>
      <p>This app is currently under development, and some of its features may not be fully functional yet.  Thank you for understanding.</p>
  </div>
  <div class="modal-footer">
    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Ok</a>
  </div>
</div>

<script>
 $(document).ready(function(){
    $('#under_construction').modal({
      'onOpenStart': true
    });
    <?
    if (!$_SESSION['development_notice'])
    {
      $_SESSION['development_notice'] = true;
      echo '$(\'#under_construction\').modal(\'open\')';
    }
    ?>
});

</script>
