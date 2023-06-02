

<!-- Bookmarks Title Modal Structure -->
<div id="bookmark_collection_title" class="modal">
   <div class="modal-content">
        <h4>Bookmark Collection Title</h4>
        <div class="row">
            <div class="input-field col s6">
                <label for="bookmarks_title">Filename</label>
                <input class="" data-default-filename="kjvbot-bookmarks-<?= date("m-d-Y") ?>-<?= time() ?>.json" placeholder="kjvbot-bookmarks-<?= date("m-d-Y") ?>-<?= time() ?>.json (Default)" id="bookmarks_title" type="text">
                <span class="helper-text">Would you like to customize the filename before saving?</span>
            </div>
        </div>
        <div class="modal-footer">
        <a href="#!" class="export_btn waves-effect waves-white btn-flat">Export</a>
        <a href="#!" class="modal-close waves-effect waves-white btn-flat">Cancel</a>
        </div>
    </div>
</div>
