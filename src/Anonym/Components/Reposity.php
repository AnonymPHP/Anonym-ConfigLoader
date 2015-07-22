<?php
    /**
     * Bu Dosya AnonymFramework'e ait bir dosyadır.
     *
     * @author vahitserifsaglam <vahit.serif119@gmail.com>
     * @see http://gemframework.com
     *
     */

    namespace Anonym\Components\Config;
    use ArrayAccess;
    /**
     * @author vahitserifsaglam <vahit.serif119@gmail.com>
     * @copyright AnonymMedya, 2015
     */
    class Reposity implements ArrayAccess
    {
        private $cache;
        public function __construct(array $cache = [])
        {
            $this->setCache($cache);
        }

        /**
         * @return array
         */
        public function getCache()
        {
            return $this->cache;
        }

        /**
         * @param array $cache
         * @return Reposity
         */
        public function setCache($cache)
        {
            $this->cache = $cache;

            return $this;
        }


        /**
         * @param string $name
         * @return bool
         */
        public function has($name = '')
        {
            $get = $this->get($name);
            if (null !== $get) {
                return $get;
            } else {
                return false;
            }
        }
        /**
         * İstenilen ayarı döndürür
         *
         * @param string $config
         * @return boolean|mixed
         * @access public
         */
        public function get($config)
        {
            $parse = $this->parse($config);
            if (count($parse) === 1) {
                $task = $parse[0];
            } elseif (count($parse) === 2) {
                list($task, $method) = $parse;
            } elseif (count($parse) === 3) {
                list($task, $method, $fname) = $parse;
            }
            if (isset($this->cache[$task])) {
                $return = $this->cache[$task];
            } else {
                return null;
            }
            if (isset($method)) {
                if (isset($return[$method])) {
                    $return = $return[$method];
                    if (isset($fname)) {
                        if (isset($return[$fname])) {
                            $return = $return[$fname];
                        }
                    }
                }
            }
            return $return;
        }
        /**
         * Metni . karekterine göre parçalar ve görev listesini oluşturur
         *
         * @param string $config
         * @return array|string
         */
        private static function parse(
            $config = ''
        ) {
            if (strstr($config, ".")) {
                $parse = explode('.', $config);
                return $parse;
            } else {
                return (array)$config;
            }
        }
        /**
         * @param string $name verinin ismi
         * @param string $value değeri
         */
        public function set($name, $value = '')
        {
            if (!strstr($name, ".")) {
                $this->cache[$name] = $value;
            } else {
                $parse = $this->parse($name);
                if (count($parse) === 2) {
                    list($name, $fname) = $parse;
                    $this->cache[$name][$fname] = $value;
                } elseif (count($parse) === 3) {
                    list($name, $fname, $sname) = $parse;
                    $this->cache[$name][$fname][$sname] = $value;
                }
            }
        }
        /**
         * @param string $name eklenecek değerin ismi
         * @param string $value değeri
         */
        public function add($name = '', $value = '')
        {
            if (!strstr($name, ".")) {
                $this->cache[$name] = array_merge($this->cache[$name], [$value]);
            } else {
                $parse = $this->parse($name);
                if (count($parse) === 2) {
                    list($name, $fname) = $parse;
                    $this->cache[$name][$fname] = array_merge($this->cache[$name][$fname][], [$value]);
                } elseif (count($parse) === 3) {
                    list($name, $fname, $sname) = $parse;
                    $this->cache[$name][$fname][$sname] = array_merge($this->cache[$name][$fname][$sname], [$value]);
                }
            }
        }
        /**
         * Dizi olarak erişilirken itemin olup olmadığına bakılır
         *
         * @param  string $key
         * @return bool
         */
        public function offsetExists($key)
        {
            return $this->has($key);
        }
        /**
         * Dizi olarak erişilirken Veri çekmekte kullanılır
         *
         * @param  string $key
         * @return mixed
         */
        public function offsetGet($key)
        {
            return $this->get($key);
        }
        /**
         * Dizi olarak erişilirken veri eklemede kullanılır
         *
         * @param  string $key
         * @param  mixed $value
         * @return void
         */
        public function offsetSet($key, $value)
        {
            $this->set($key, $value);
        }
        /**
         * Array olarak erişilirken veri unset edildiğinde yapılır
         *
         * @param  string $key
         * @return void
         */
        public function offsetUnset($key)
        {
            $this->set($key, null);
        }
    }
