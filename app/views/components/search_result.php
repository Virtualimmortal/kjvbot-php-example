<?php

if ($result['type'] == 'keyword')
{
   echo $this->render('components/keyword_search_results_tabs.php', array('result' => $result));
}
if ($result['type'] == 'verse')
{
   echo $this->render('components/verse_search_result.php', array('result' => $result));
}
