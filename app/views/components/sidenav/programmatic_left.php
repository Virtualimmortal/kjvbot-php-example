<ul id="slide-out" class="sidenav bookmark_tools show-on-large">
    <li>
        <h4 class="menu_header"><img class="brand-logo center" src="images/kjvbot-logo-96.png"></h4>
    </li>
    <li><hr/></li>
    <li class="no-padding">
        <a class="nightModeTrigger">
            Light/Dark
            <div class="switch right">
                <label>
                    <input class="checkbox-blue filled-in" type="checkbox" <?= ($night_mode) ? 'checked' : '' ?>>
                    <span class="lever "></span>
                </label>
            </div>
        </a>
    </li>
    <li><hr/></li>
    <li>
        <a class="import_btn"><i class="material-icons">cloud_upload</i>Import</a>
    </li>
    <li>
        <a href="api.php?action=get_session_bookmarks&type=file"><i class="material-icons">cloud_download</i>Export</a>
    </li>
    <li>
        <a class="clear_all_bookmarks_btn"><i class="material-icons">delete</i>Remove All</a>
    </li>
    <li><hr/></li>
    <li>
        <a class="share_btn" data-link="https://mybiblebot.net/search.php?s=<?= urlencode(implode(';', $_SESSION['bookmarks'])) ?>"><i class="material-icons">share</i>Share</a>
    </li>
    <li><hr/></li>
</ul>
