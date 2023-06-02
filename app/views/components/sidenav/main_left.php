<?php 
$night_mode = ((!$_SESSION['day_night_mode']) || ($_SESSION['day_night_mode'] == 'night') || ($day_night_mode == 'night'));
?>
<ul id="slide-out" class="sidenav show-on-large">
    <li>
        <div class="home-view center-align">
            <a style="padding: 0; display: block;" href="/mediabrain">
                <i class="material-icons large grey-text text-darken-2">home</i>
            </a>
        </div>
    </li>

    <li><hr/></li>
    <li class="no-padding">
        <? render('components/table_of_contents.php'); ?>
    </li>
    <li class="no-padding">
        <? render('components/bookmarks_menu.php'); ?>
    </li>
    <li><hr/></li>
    <li class="no-padding">
      <a target="_blank" href="//vi/mediabrain/?app=bibleProject"><i class="material-icons">play_circle_outline</i> BibleProject Videos</a>
    </li>
    <li><hr/></li>
    <li class="no-padding">
        <a class="nightModeTrigger">
            Display Mode
            <div class="switch right">
                <label>
                    Light
                    <input class="checkbox-blue filled-in" type="checkbox" <?= ($night_mode) ? 'checked' : '' ?>>
                    <span class="lever "></span>
                    Dark
                </label>
            </div>
        </a>
    </li>
    <li><hr/></li>
    <li class="no-padding">
        <a target="_blank" href="http://vi/kjvbot/landings/embed-demo/template1.htm"><i class="far fa-eye"></i>Embed Demo</a>
    </li>
    <li><hr/></li>
    <? /*
    <li class="no-padding">
        <a class="modal-trigger" data-target="contribute_dialog" ><i class="fas fa-donate"></i>Contribute</a>
    </li>
    <li><hr/></li>
    */ ?>
</ul>


<? //render('components/dialogs/contribute.php'); ?>
