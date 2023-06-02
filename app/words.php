<?php


/**
 * Class Words
 *
 * @method $this meansLike(string $text = null)
 * @method $this soundsLike(string $text = null)
 * @method $this spelledLike(string $text = null)
 * @method $this nounsByAdjective(string $text = null)
 * @method $this adjectiveByNoun(string $text = null)
 * @method $this synonymsOf(string $text = null)
 * @method $this triggersOf(string $text = null)
 * @method $this antonymsOf(string $text = null)
 * @method $this moreSpecificThan(string $text = null)
 * @method $this moreGeneralThan(string $text = null)
 * @method $this comprisesOf(string $text = null)
 * @method $this partOf(string $text = null)
 * @method $this wordsFollowing(string $text = null)
 * @method $this wordsPreceding(string $text = null)
 * @method $this perfectRhymes(string $text = null)
 * @method $this approxRhymes(string $text = null)
 * @method $this homophonesOf(string $text = null)
 * @method $this matchesConsonant(string $text = null)
 * @method $this ofTopic(string $text = null)
 * @method $this leftContext(string $text = null)
 * @method $this rightContext(string $text = null)
 */
class Words
{
    /**
     * @var array
     */
    protected $result = [];
    /**
     * @var int
     */
    protected $resultCount = 0;
    /**
     * @var null|string
     */
    protected $resultFrom = null;

    /**
     * @var array
     */
    protected $parameters = [];
    /**
     * @var null
     */
    protected $query = null;
    /**
     * @var null|string
     */
    protected $queryUrl = null;

    /**
     * @var array
     */
    protected $tmp = [];

    /**
     * @var array
     */
    protected $availableParameters = [
        'ml'      => 'means like',
        'sl'      => 'sounds like',
        'sp'      => 'spelled like',
        'rel_jja' => 'nouns by adjective',
        'rel_jjb' => 'adjectives by noun',
        'rel_syn' => 'synonyms of',
        'rel_trg' => 'triggers of',
        'rel_ant' => 'antonyms of',
        'rel_spc' => 'more specific than',
        'rel_gen' => 'more general than',
        'rel_com' => 'comprises of',
        'rel_par' => 'part of',
        'rel_bga' => 'words following',
        'rel_bgb' => 'words preceding',
        'rel_rhy' => 'perfect rhymes',
        'rel_nry' => 'approximate rhymes',
        'rel_hom' => 'homophones of',
        'rel_cns' => 'matches consonants',
        'topics'  => 'of topic',
        'lc'      => 'left context',
        'rc'      => 'right context',
    ];

    /**
     * @var bool
     */
    protected $cacheEnable = false;
    /**
     * @var int
     */
    protected $cacheLifetime = 86400;
    /**
     * @var string
     */
    protected $cacheDir = __DIR__.'/cache';
    /**
     * @var FileCache
     */
    protected $cache;

    /**
     * @param string $name
     * @param mixed  $args
     * @return $this
     */
    public function __call($name, $args)
    {
        if (!isset($args[0])) {
            return $this;
        }
        $str = (string)Str::normalCase($name);
        foreach ($this->availableParameters as $key => $value) {
            if ($value == $str || $key == $str) {
                $this->setOpt($key, $args[0]);
                break;
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }

    /**
     * ApiClient constructor.
     * @param array|null $args
     */
    public function __construct(array $args = null)
    {
        foreach (['cache_dir', 'cache_lifetime', 'cache_enable'] as $key) {
            if (isset($args[$key])) {
                $this->{$key} = $args[$key];
                unset($args[$key]);
            }
        }
        $this->setOpts($args);

        if ($this->cacheEnable) {
            $this->cache = new FileCache(['cache_dir' => $this->cacheDir]);
        }
    }

    /**
     * @param array|null $args
     * @return $this
     */
    public function setOpts(array $args = null)
    {
        if (is_array($args)) {
            foreach ($args as $key => $value) {
                $this->setOpt((string)$key, (string)$value);
            }
        }
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setOpt(string $key, string $value)
    {
        if (isset($this->availableParameters[$key])) {
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function getWords()
    {
        if (count($this->parameters)) {
            $url = 'https://api.datamuse.com/words?';
            $this->parameters['max'] = 1000;
            $query = [];
            foreach ($this->parameters as $key => $value) {
                $value = urlencode($value);
                $url .= "{$key}={$value}&";
                if ($key != 'max') {
                    $query[] = "{$this->availableParameters[$key]} `{$value}`";
                }
            }
            $url = rtrim($url, '&');
            $content = $this->cacheEnable ? $this->cache->get($url) : null;
            $this->resultFrom = 'cache';
            if (!$content) {
                $this->resultFrom = 'api';
                $content = file_get_contents($url);
                if (strlen($content) && $content = json_decode($content)) {
                    if ($this->cacheEnable) {
                        $this->cache->save($url, $content, $this->cacheLifetime);
                    }
                }
            }
            $this->result = $content;
            $this->resultCount = count($content);
            $this->query = implode("\n", $query);
            $this->queryUrl = Str::afterFirst($url, '?')->toString();
        }
        return $this;
    }

    /**
     * @param string $parameter
     * @return mixed|null
     */
    public function getParameter(string $parameter)
    {
        return isset($this->parameters[$parameter]) ? $this->parameters[$parameter] : null;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
