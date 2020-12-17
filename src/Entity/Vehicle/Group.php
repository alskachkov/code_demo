<?php

namespace Euroauto\Vehicle
{
    class Group
    {
        protected $_id = NULL;
        protected $_data = array();

        public static function factory($id, array $data = array())
        {
            return new self($id, $data);
        }

        public function __construct($id, array $data = array())
        {
            $this->_id = $id;
            $this->_data = $data;
        }

        public function get_id()
        {
            return $this->_id;
        }

        public function get_name()
        {
            return $this->get_data_value('mname');
        }

        public function get_urn()
        {
            return $this->get_data_value('URN');
        }

        public function as_old_array()
        {
            return $this->_data;
        }

        /**
         * Возвращает значение из внутреннего массива данных
         * @param $key
         * @param $default
         * @return mixed
         */
        protected function get_data_value($key, $default = NULL)
        {
            return isset($this->_data[$key]) ? $this->_data[$key] : $default;
        }
    }
}