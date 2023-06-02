<?php
require '../includes/app.php';
require '../includes/classes/bible.php';
$app = App::getInstance();
$bible = Bible::getInstance();
$render = get_var('render', false);

$reference = get_var('r', 0);
if (!is_numeric($reference))
{
   $reference = explode('-', $reference);
   $reference = convertToNumber($reference[0]);
}
$limit = get_var('l', 10);
$forward = get_var('d', 1);
if ($forward)
{
   debug('Reading from - ' . get_var('r'));
   $results = $bible->next($reference, $limit);
}
else 
{
   debug('Reverse reading from - ' . get_var('r'));
   $results = $bible->previous($reference, $limit);
}

if ($render)
{
   $markup = array();
   foreach ($results as $verse)
   {
      $book = $bible->utils->booksFullnames[$verse['b']-1];
      if (($verse['v'] == '1') || (count($results) == 1))
      {
         $current_chapter = $book . ' ' . $verse['c'];
      }
      else
      {
         $current_chapter = '';
      }
      $book_number = bible_to_sql::addZeros($verse['b'], 2);
      $chapter = bible_to_sql::addZeros($verse['c'], 3);
      $verse_number = bible_to_sql::addZeros($verse['v'], 3);
      $verse_id = $book_number . $chapter . $verse_number;
      $verse['reference'] = Bible::getInstance()->utils->friendlyVerseId($verse_id);
      $verse['url'] = 'search.php?s=' . urlencode($verse['reference']);

      $markup[] = render('components/verse.php', array('chapter' => $current_chapter, 'verse' => $verse), 1);
   }
   $results = $markup;
}

$json = array(
   'type' => 'verse',
   'data' => $results,
);
header('Content-Type: application/json');
echo json_encode($json);
