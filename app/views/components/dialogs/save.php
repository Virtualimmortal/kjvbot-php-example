<style>
#save_dialog .files {
   
}
#save_dialog .files .file {
   cursor: pointer;
}
#save_dialog .files.icon-view {
   padding-bottom: 4rem;
}
#save_dialog .files.icon-view .file {
   margin: 1rem 1rem 0;
   display: inline-block;
   vertical-align: top;
}
#save_dialog .files.icon-view .file a {
   width: 80px;
   height: 125px;
   background: url("images/kjvbot-logo-96.png") no-repeat;
   background-size: 60px 60px;
   background-position: center top;
   padding-top: 60px;
   margin-bottom: 1rem;
   display: block;
   overflow: hidden;
}
#save_dialog .files.icon-view .file.selected {
   background-color: #eee;
}
#save_dialog .files.icon-view .file.selected a {
   overflow: visible;
   height: auto;
   border: 1px dashed;
   margin-bottom: 0;
   overflow: hidden;
}
#save_dialog input.filename {
   position: fixed;
   padding-bottom: 1rem;
   bottom: 3rem;
   width: 85%;
   font-size: 1.7em;
   background-color: white;
   border-top: 1px solid #9B9B9B;
}

#save_dialog input.filename::placeholder {
   color: #333;
   opacity: 1; 
}

}
</style>
<!-- Modal Structure -->
<div id="save_dialog" class="modal modal-fixed-footer">
   <div class="modal-content grey-text text-darken-3">
      <h4>Save</h4>
      <ul class="files icon-view no-text-select">
      </ul>
      <input class="filename" type="text" placeholder="Filename" />
   </div>
   <div class="modal-footer">
      <a class="modal-close waves-effect waves-green btn-flat btnSave">Save</a>
      <a class="modal-close waves-effect waves-red btn-flat btnCancel">Cancel</a>
   </div>
</div>
