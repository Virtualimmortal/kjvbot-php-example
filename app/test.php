<?php
require '../app/includes/app.php';
require 'bible.php';

//$search_string = '?s=study%3B+Psalms+110%3A1%3B+2+Corinthians+4%3A3-4%3B+wept%3B+Ephesians+4%3A1%2C3%2C5%2C7-9%3B+believe%3B+Jesus';
//$search_string = 'study; Psalms 110:1; 2 Corinthians 4:3-4; wept; Ephesians 4:1,3,5,7-9; believe; Jesus';
$search_string = get_var('s');
$search_keys = explode(";", $search_string);
$scripture_keys = array();
$keywords = array();
foreach ($search_keys as $search_key)
{
   preg_match('/([1|2|3]?([i|I]+)?(\s?)\w+(\s+?))((\d+)?(,?)(\s?)(\d+))+(:?)((\d+)?([\-–]\d+)?(,(\s?)\d+[\-–]\d+)?)?/', $search_key, $matches);
   
   if (!empty($matches))
   {
      $scripture_keys[] = $search_key;
   }
   else
   {
      $keywords[] = trim($search_key);
   }
}

$scripture_keys = array_map('trim', $scripture_keys);
$keywords = array_map('trim', $keywords);
$search_keys = array_map('trim', $search_keys);

$search_keys = array(
   'references' => $scripture_keys,
   'keywords' => $keywords,
   'combined' => $search_keys,
   'search_string' => $search_string,
);
//debug($search_keys, 'Constructed $search_keys');

$synonyms = array();
foreach ($search_keys['keywords'] as $index => $r) 
{
   $synonyms[] = getSynonyms($r);
}

debug($synonyms);







