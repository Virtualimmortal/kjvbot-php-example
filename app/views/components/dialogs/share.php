<style>

</style>

<!-- Modal Structure -->
<div id="share_dialog" class="modal modal-fixed-footer">
   <div class="modal-content">
      <h4>Share</h4>

      <h5>Link</h5>
      <div class="row">
         <div class="col s12 m8">
            <div class="input-field meme-select-wrapper">
               <select id="meme_select" class="icons">
                  <option value="/images/memes/2.jpg" disabled selected>Select an image</option>
                  <?php
                  $images_dir = getcwd() . '/images/memes';
                  $href_path = '/images/memes';
                  $images = get_dir_contents($images_dir);
                  $types=Array(1 => 'jpg', 2 => 'jpeg', 3 => 'png', 4 => 'gif'); //store all the image extension types in array

                  $imgname = ""; //get image name here
                  foreach ($images as $image)
                  {
                     $image = str_replace($images_dir, '/images/memes', $image);
                     $filename = basename(str_replace('/images/memes/', '', $image));
                     $ext = explode(".",$filename); 
                     $ext = $ext[count($ext)-1];
                     if (($filename != '2.jpg') && (in_array($ext,$types)))
                     {
                     ?>
                     <option value="<?= $href_path . '/' . $filename ?>" data-icon="<?= $href_path . '/' . $filename ?>"><?= $filename ?></option>
                     <?
                     }
                  }
                  ?>
               </select>
               <label>Image</label>
            </div>
            <div class="input-field">
               <input id="page_link_text" type="text" value="<?= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']. '?' . $_SERVER['QUERY_STRING'] ?>"></input><button title="Page link" class="btn indigo copy_page_link_btn">Copy</button>

            </div>
         </div>
         <div class="col s12 m4">
            <img class="meme_preview responsive-img" src="/images/memes/2.jpg" style="max-height: 10em;"/>
         </div>
      </div>
      <hr></hr>
      <h5>Embed</h5>
      <div class="row">
         <div class="col s12">
            <div class="input-field">
               <textarea id="page_embed_code"><iframe src="<?= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']. '?' . $_SERVER['QUERY_STRING'] ?>" name="kjvbot" scrolling="Yes" height="500px" width="100%" style="border: none;"></iframe></textarea><button title="Page link" class="btn indigo copy_page_embed_code_btn">Copy</button>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-white btn-flat btnOk">Ok</a>
   </div>
</div>
