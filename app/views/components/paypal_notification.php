<div id="paypalDonateModal" class="modal bottom-sheet modal-fixed-footer black">
   <div class="modal-content">
      <div class="container center-align">
         <h4>Help!</h4>
         <h5>The bill collector is going to get me!</h5>
         <p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick" />
               <input type="hidden" name="hosted_button_id" value="NEV9SM2N3NLAW" />
               <input id="paypalDonate" type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
               <img id="paypalDonate" alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
            </form>
         </p>
      </div>
   </div>
   <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-light btn-flat">No Thanks</a>
   </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

   function showThenHide()
   {
      $('#paypalDonateModal').modal('open');
      setTimeout(function(){ $('#paypalDonateModal').tapTarget('close'); }, 10000);
   }

   $('#paypalDonateModal').modal();
   <?
   if (config('request_donations'))
      echo 'setTimeout(showThenHide, '.config('request_donations_timeout').');';
   ?>
});
</script>
