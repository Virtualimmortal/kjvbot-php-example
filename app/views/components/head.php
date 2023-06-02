<!DOCTYPE html>
<html lang="en">

<head>

   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
   <meta name="google" value="notranslate">

   <meta name="title" content="<?= $meta['title']; ?>">
   <meta name="description" content="<?= $meta['description']; ?>">
   <meta name="author" content="Mediabrain">
   <meta property="og:type" content="<?= $meta['type']; ?>" />
   <meta property="og:title" content="<?= $meta['title']; ?>" />
   <meta property="og:description" content="<?= $meta['description']; ?>" />
   <?
   if ((isset($meta['video'])) && (!isset($meta['image'])))
   {
      ?>
      <meta property="og:video" content="<?= $meta['video']; ?>" />
      <meta property="og:video:width" content="<?= $meta['video_width']; ?>" />
      <meta property="og:video:height" content="<?= $meta['video_height']; ?>" />
      <?php
   }
   else
   {
      ?>
      <meta property="og:image" content="<?= $meta['image']; ?>" />
      <meta property="og:image:width" content="<?= $meta['image_width']; ?>" />
      <meta property="og:image:height" content="<?= $meta['image_height']; ?>" />
      <?php
   }
   ?>

   <title><?= $meta['title']; ?></title>
   <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
   <link rel="icon" type='image/png' sizes='256x256' href="favicon.png"/>
   <link rel="apple-touch-icon" sizes="152x152" href="apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
   <link rel="manifest" href="site.webmanifest"><!-- CSS  -->
   <!--Import Google Icon Font-->
   <link rel="stylesheet" href="css/icons.css">
   <!--Import materialize.css-->
   <link rel="stylesheet" href="css/fa/css/fa.all.min.css"/>
   <link rel="stylesheet" href="css/materialize.min.css"/>
   <!--Import primary css-->
   <link rel="stylesheet" href="css/style.css"/>
   <link rel="stylesheet" href="css/respond.css"/>
   <link rel="stylesheet" href="css/jquery.json-viewer.css">
   <script>var mb = {};</script>
   <script src="js/jquery-2.1.1.min.js"></script>
   <script src="js/jquery.json-viewer.js"></script>
   
   <script src="js/overlay.js" type="text/javascript"></script>
   <script src="js/Sortable.min.js" type="module"></script>
   <script src="js/MultiDrag.js" type="module"></script>
   <script src="js/mediabrain.js"></script>
   <!--<script src="/js/dialogs/create_new_bookmark.js"></script>-->

</head>
