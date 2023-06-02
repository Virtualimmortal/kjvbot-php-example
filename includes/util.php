<?php
/**
 * Mediabrain App Utility Functions
 */


function render($filename, $vars = array(), $return = false)
{
   $app = App::getInstance();
   
   if ($return)
   {
      return $app->render($filename, $vars);
   }
   else 
   {
      echo $app->render($filename, $vars);
   }
}


function config($var = null)
{
   $protocol = protocol();
   $sites =  array(
      'Z:\cloud\web\git\kjvbot\kjvbot.local\app' => array(
        'under_construction' => false,
        'request_donations' => false,
        'request_donations_timeout' => 15000,  // Time after page load to display PP Donate
        'base_url' => $protocol.'kjvbot.local',
        'mysql' => array(
           'server' => 'localhost',
           'username' => 'webuser',
           'password' => 'H7_lsfodOEep043L',
           'db' => 'kjvbot',
        ),
     ),
  );
   $path = getcwd();

   if (isset($var))
      return $sites[$path][$var];

   return ((isset($key)) && (isset($sites[$path][$key]))) 
      ? $sites[$path][$key] 
      : $sites[$path];
}


function protocol()
{
  return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
}


function get_var($name, $default = null)
{
   return (isset($_REQUEST[$name]))
   ? $_REQUEST[$name]
   : $default;
}


function get_path($type, $name) {
  return dirname(drupal_get_filename($type, $name));
}


function debug($object, $message = 'Debug Output')
{
   $log_file = 'c:/wamp64/logs/php_error.log';
   $backtrace = debug_backtrace();
   $caller = array_shift($backtrace);
   $caller['args'] = array_shift($caller['args']);
   error_log('------------------------------------------' . "\n\n", 3, $log_file);
   error_log($message . "\n\n", 3, $log_file);
   error_log(date("D M j G:i:s T Y") . "\n", 3, $log_file);
   error_log($_SERVER['HTTP_USER_AGENT'] . "\n", 3, $log_file);
   error_log($_SERVER['REMOTE_ADDR'] . "\n", 3, $log_file);
   error_log(print_r($caller,true) . "\n", 3, $log_file);
   error_log('------------------------------------------' . "\n\n", 3, $log_file);
}


function module_invoke($module, $hook) {
   $args = func_get_args();
   // Remove $module and $hook from the arguments.
   unset($args[0], $args[1]);
   if (module_hook($module, $hook)) {
      return call_user_func_array($module . '_' . $hook, $args);
   }
}


function module_hook($module, $hook) {
   $function = $module . '_' . $hook;
   if (function_exists($function)) {
      return TRUE;
   }

   // If the hook implementation does not exist, check whether it may live in an
   // optional include file registered via hook_hook_info().
   $hook_info = module_hook_info();
   if (isset($hook_info[$hook]['group'])) {
      module_load_include('inc', $module, $module . '.' . $hook_info[$hook]['group']);
      if (function_exists($function)) {
         return TRUE;
      }
   }
   return FALSE;
}


function module_hook_info() {

  // This function is indirectly invoked from bootstrap_invoke_all(), in which
  // case common.inc, subsystems, and modules are not loaded yet, so it does not
  // make sense to support hook groups resp. lazy-loaded include files prior to
  // full bootstrap.
  if (drupal_bootstrap(NULL, FALSE) != DRUPAL_BOOTSTRAP_FULL) {
    return array();
  }
  $hook_info =& drupal_static(__FUNCTION__);
  if (!isset($hook_info)) {
    $hook_info = array();
    $cache = cache_get('hook_info', 'cache_bootstrap');
    if ($cache === FALSE) {

      // Rebuild the cache and save it.
      // We can't use module_invoke_all() here or it would cause an infinite
      // loop.
      foreach (module_list() as $module) {
        $function = $module . '_hook_info';
        if (function_exists($function)) {
          $result = $function();
          if (isset($result) && is_array($result)) {
            $hook_info = array_merge_recursive($hook_info, $result);
          }
        }
      }

      // We can't use drupal_alter() for the same reason as above.
      foreach (module_list() as $module) {
        $function = $module . '_hook_info_alter';
        if (function_exists($function)) {
          $function($hook_info);
        }
      }
      cache_set('hook_info', $hook_info, 'cache_bootstrap');
    }
    else {
      $hook_info = $cache->data;
    }
  }
  return $hook_info;
}


function module_list($refresh = FALSE, $bootstrap_refresh = FALSE, $sort = FALSE, $fixed_list = NULL) {
  static $list = array(), $sorted_list;
  if (empty($list) || $refresh || $fixed_list) {
    $list = array();
    $sorted_list = NULL;
    if ($fixed_list) {
      foreach ($fixed_list as $name => $module) {
        get_filename('module', $name, $module['filename']);
        $list[$name] = $name;
      }
    }
    else {
      if ($refresh) {

        // For the $refresh case, make sure that system_list() returns fresh
        // data.
        drupal_static_reset('system_list');
      }
      if ($bootstrap_refresh) {
        $list = system_list('bootstrap');
      }
      else {

        // Not using drupal_map_assoc() here as that requires common.inc.
        $list = array_keys(system_list('module_enabled'));
        $list = !empty($list) ? array_combine($list, $list) : array();
      }
    }
  }
  if ($sort) {
    if (!isset($sorted_list)) {
      $sorted_list = $list;
      ksort($sorted_list);
    }
    return $sorted_list;
  }
  return $list;
}


function module_load_include($type, $module, $name = NULL) {
  static $files = array();
  if (!isset($name)) {
    $name = $module;
  }
  $key = $type . ':' . $module . ':' . $name;
  if (isset($files[$key])) {
    return $files[$key];
  }
  if (function_exists('get_path')) {
    $file = APP_ROOT . '/' . get_path('module', $module) . "/{$name}.{$type}";
    if (is_file($file)) {
      require_once $file;
      $files[$key] = $file;
      return $file;
    }
    else {
      $files[$key] = FALSE;
    }
  }
  return FALSE;
}


function get_dir_contents($dir, &$results = array()) {
  $files = scandir($dir);

  foreach ($files as $key => $value) {
      $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
      if (!is_dir($path)) {
          $results[] = $path;
      } else if ($value != "." && $value != "..") {
          getDirContents($path, $results);
          $results[] = $path;
      }
  }

  return $results;
}

function error($message, $object = null)
{
   $log_file = 'c:/wamp64/logs/php_error.log';
   $backtrace = debug_backtrace();
   $caller = array_shift($backtrace);
   error_log('--------------- Error ---------------------' . "\n\n", 3, $log_file);
   error_log($message . "\n\n", 3, $log_file);
   error_log(date("D M j G:i:s T Y") . "\n", 3, $log_file);
   error_log(print_r($caller,true) . "\n", 3, $log_file);
   error_log('------------------------------------------' . "\n\n", 3, $log_file);
}


function getSynonyms($string)
{
  return;
  
   $bad_words = array('4r5e','5h1t','5hit','a55','anal','anus','ar5e','arrse','arse','ass','ass-fucker','asses','assfucker','assfukka','asshole','assholes','asswhole','a_s_s','b!tch','b00bs','b17ch','b1tch','ballbag','balls','ballsack','bastard','beastial','beastiality','bellend','bestial','bestiality','bi+ch','biatch','bitch','bitcher','bitchers','bitches','bitchin','bitching','bloody','blow job','blowjob','blowjobs','boiolas','bollock','bollok','boner','boob','boobs','booobs','boooobs','booooobs','booooooobs','breasts','buceta','bugger','bum','bunny fucker','butt','butthole','buttmuch','buttplug','c0ck','c0cksucker','carpet muncher','cawk','chink','cipa','cl1t','clit','clitoris','clits','cnut','cock','cock-sucker','cockface','cockhead','cockmunch','cockmuncher','cocks','cocksuck','cocksucked','cocksucker','cocksucking','cocksucks','cocksuka','cocksukka','cok','cokmuncher','coksucka','coon','cox','crap','cum','cummer','cumming','cums','cumshot','cunilingus','cunillingus','cunnilingus','cunt','cuntlick','cuntlicker','cuntlicking','cunts','cyalis','cyberfuc','cyberfuck','cyberfucked','cyberfucker','cyberfuckers','cyberfucking','d1ck','dammit','damnit','dang','damn','dick','dickhead','dildo','dildos','dink','dinks','dirsa','dlck','dog-fucker','doggin','dogging','donkeyribber','doosh','duche','dyke','ejaculate','ejaculated','ejaculates','ejaculating','ejaculatings','ejaculation','ejakulate','f u c k','f u c k e r','f4nny','fag','fagging','faggitt','faggot','faggs','fagot','fagots','fags','fanny','fannyflaps','fannyfucker','fanyy','fatass','fcuk','fcuker','fcuking','feck','fecker','felching','fellate','fellatio','fingerfuck','fingerfucked','fingerfucker','fingerfuckers','fingerfucking','fingerfucks','fistfuck','fistfucked','fistfucker','fistfuckers','fistfucking','fistfuckings','fistfucks','flange','fook','fooker','fuck','fucka','fucked','fucker','fuckers','fuckhead','fuckheads','fuckin','fucking','fuckings','fuckingshitmotherfucker','fuckme','fucks','fuckwhit','fuckwit','fudge packer','fudgepacker','fuk','fuker','fukker','fukkin','fuks','fukwhit','fukwit','fux','fux0r','f_u_c_k','gangbang','gangbanged','gangbangs','gaylord','gaysex','goatse','god-dam','god-damned','goddamn','goddamned','hardcoresex','hell','heshe','hoar','hoare','hoer','homo','hore','horniest','horny','hotsex','jack-off','jackoff','jap','jerk-off','jism','jiz','jizm','jizz','kawk','knob','knobead','knobed','knobend','knobhead','knobjocky','knobjokey','kock','kondum','kondums','kum','kummer','kumming','kums','kunilingus','l3i+ch','l3itch','labia','lmfao','lust','lusting','m0f0','m0fo','m45terbate','ma5terb8','ma5terbate','masochist','master-bate','masterb8','masterbat*','masterbat3','masterbate','masterbation','masterbations','masturbate','mo-fo','mof0','mofo','mothafuck','mothafucka','mothafuckas','mothafuckaz','mothafucked','mothafucker','mothafuckers','mothafuckin','mothafucking','mothafuckings','mothafucks','mother fucker','motherfuck','motherfucked','motherfucker','motherfuckers','motherfuckin','motherfucking','motherfuckings','motherfuckka','motherfucks','muff','mutha','muthafecker','muthafuckker','muther','mutherfucker','n1gga','n1gger','nazi','nigg3r','nigg4h','nigga','niggah','niggas','niggaz','nigger','niggers','nob','nob jokey','nobhead','nobjocky','nobjokey','numbnuts','nutsack','orgasim','orgasims','orgasm','orgasms','p0rn','pawn','pecker','penis','penisfucker','phonesex','phuck','phuk','phuked','phuking','phukked','phukking','phuks','phuq','pigfucker','pimpis','piss','pissed','pisser','pissers','pisses','pissflaps','pissin','pissing','pissoff','poop','porn','porno','pornography','pornos','prick','pricks','pron','pube','pusse','pussi','pussies','pussy','pussys','rectum','retard','rimjaw','rimming','s hit','s.o.b.','sadist','schlong','screwing','scroat','scrote','scrotum','semen','sex','sh!+','sh!t','sh1t','shag','shagger','shaggin','shagging','shemale','shi+','shit','shitdick','shite','shited','shitey','shitfuck','shitfull','shithead','shiting','shitings','shits','shitted','shitter','shitters','shitting','shittings','shitty','skank','slut','sluts','smegma','smut','snatch','son-of-a-bitch','spac','spunk','s_h_i_t','t1tt1e5','t1tties','teets','teez','testical','testicle','tit','titfuck','tits','titt','tittie5','tittiefucker','titties','tittyfuck','tittywank','titwank','tosser','turd','tw4t','twat','twathead','twatty','twunt','twunter','v14gra','v1gra','vagina','viagra','vulva','w00se','wang','wank','wanker','wanky','whoar','whore','willies','xrated','xxx');
   
   $json = file_get_contents('https://api.datamuse.com/words?ml=' . urlencode($string));
   $obj = json_decode($json);
   $synonyms = array();
   if ((is_array($obj)) && (count($obj)))
   {
      foreach ($obj as $i => $o)
      {
         if (!in_array($o->word, $bad_words))
            $synonyms[] = $o;
      }
      $synonyms = array_slice($synonyms, 0, 30);
   }
   //debug($synonyms, $string);
   return $synonyms;
}


