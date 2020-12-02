<?php

namespace App;

use Phalcon\Di;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use App\Shared\Models\Translation;

class Translator
{
    /** @var string */
    protected string $_lang, $_fallbackLang;

    /** @var array */
    protected array $_single, $_group;

    /** @var self */
    private static Translator $_instance;

    private function __construct()
    {
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

    /**
     * @param string $key
     * @param array $params replace substrings
     * @return string
     * @throws \Phalcon\Cache\Exception\InvalidArgumentException
     */
    public function translate(string $key, array $params = []): string
    {
        $collection = $this->_load($this->_lang);
        return $collection->exists($key) ? $collection->t($key, $params) : $key;
    }

    /**
     * @param string $lang shorthand language code
     * @return mixed|NativeArray
     * @throws \Phalcon\Cache\Exception\InvalidArgumentException
     */
    protected function _load(string $lang)
    {
        if (isset($this->_single[$lang])) { // per request cache
            return $this->_single[$lang];
        }
        return $this->_single[$lang] = new NativeArray(new InterpolatorFactory(), [
            'content' => $this->_getCachedMessages('translations-all', $lang),
        ]);
    }

    /**
     * @param string $cacheKey
     * @param string $lang
     * @param string|null $groupName
     * @return array
     * @throws \Phalcon\Cache\Exception\InvalidArgumentException
     */
    private function _getCachedMessages(string $cacheKey, string $lang, ?string $groupName = null): array
    {
        $di = Di::getDefault();
        if (! $di->has('cache')) {
            return $this->_getMessages($lang, $groupName);
        }
        /** @var \Phalcon\Cache $dataCache */
        $dataCache = $di->getShared('dataCache');
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
}
