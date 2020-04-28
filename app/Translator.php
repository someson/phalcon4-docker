<?php

namespace App;

use Phalcon\Di;
use Phalcon\Translate\Adapter\NativeArray;
use App\Shared\Models\Translation;

class Translator
{
    /** @var string */
    protected $_lang, $_fallbackLang;

    /** @var array */
    protected $_single, $_group;

    /** @var bool */
    protected $_showHelper;

    /** @var self */
    private static $_instance;

    private function __construct()
    {
        $this->_showHelper = false;
        $this->_fallbackLang = 'de';
        $this->_single = $this->_group = [];
        $this->_lang = \defined('CURRENT_LANG') ? CURRENT_LANG : $this->_fallbackLang;
    }

    private function __clone() {}

    public static function instance(): Translator
    {
        if (! self::$_instance) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function setHelperVisible(bool $flag): void
    {
        $this->_showHelper = $flag;
    }

    /**
     * @param string $lang shorhand language code
     * @return \Phalcon\Translate\AdapterInterface
     */
    protected function _loadAll(string $lang)
    {
        if (isset($this->_single[$lang])) { // per request cache
            return $this->_single[$lang];
        }
        $messages = $this->_cacheMessages('translations-all', $lang);
        return $this->_single[$lang] = new NativeArray(['content' => $messages]);
    }

    /**
     * @param string $lang shorhand language code
     * @param string $groupName
     * @return \Phalcon\Translate\AdapterInterface
     */
    protected function _loadGroup(string $lang, string $groupName)
    {
        if (isset($this->_group[$lang][$groupName])) { // per request cache
            return $this->_group[$lang][$groupName];
        }
        $messages = $this->_cacheMessages('translations-group-' . $groupName, $lang, $groupName);
        return $this->_group[$lang][$groupName] = new NativeArray(['content' => $messages]);
    }

    /**
     * @param string $cacheKey
     * @param string $lang
     * @param string|null $groupName
     * @return array
     */
    private function _cacheMessages(string $cacheKey, string $lang, ?string $groupName = null): array
    {
        if (! Di::getDefault()->has('cache')) {
            return $this->_getMessages($lang, $groupName);
        }
        /** @var \Phalcon\Cache\BackendInterface $dataCache */
        $dataCache = Di::getDefault()->getShared('cache');
        if ($cache = $dataCache->get($cacheKey)) {
            return $cache;
        }
        $messages = $this->_getMessages($lang, $groupName);
        $dataCache->save($cacheKey, $messages, Cache::getSecondsUntilTopOfNextHour());
        return $messages;
    }

    /**
     * @param string $lang
     * @param string|null $groupName
     * @return array
     */
    private function _getMessages(string $lang, ?string $groupName = null): array
    {
        $messages = [];
        foreach (Translation::findByLanguage($lang, $groupName) as $entry) {
            $messages[$entry['translation_key']] = $entry['content'];
        }
        return $messages;
    }

    /**
     * @param string $key
     * @param array $params replace substrings
     * @return string
     */
    public function _(string $key, array $params = [])
    {
        $translation = $this->_loadAll(strtoupper($this->_lang));
        if ($translation->exists($key)) {
            $string = $translation->query($key, $params);
            return $this->_showHelper ? sprintf('[OK:%s][%s]%s', $this->_lang, $key, $string) : $string;
        }
        return $this->_showHelper ? sprintf('[ERR:%s][%s]', $this->_lang, $key) : $key;
    }

    /**
     * @param string $groupName
     * @param string $key
     * @param array $params
     * @return string
     */
    public function g(string $groupName, string $key, array $params = [])
    {
        $translation = $this->_loadGroup(strtoupper($this->_lang), $groupName);
        return $translation->query($key, $params);
    }
}
