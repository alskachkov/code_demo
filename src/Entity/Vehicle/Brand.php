<?php

namespace Euroauto\Vehicle
{
    /**
     * Class Brand
     * @package Euroauto\Vehicle
     */
    class Brand implements \Euroauto\Identifiable, \JsonSerializable
    {
        /**
         * @var array
         */
        protected $_data = array();

        /**
         * Наименование бренда
         * Ford
         * @var string|null
         */
        protected $_model_firm_name = NULL;

        /**
         * Наименование страны бренда
         * Соединенные Штаты
         * @var string|null
         */
        protected $_country_name = NULL;

        /**
         * Наименование бренда по русски
         * Форд
         * @var string|null
         */
        protected $_model_firm_name_rus = NULL;

        /**
         * для передачи в urn
         * ford
         * @var string|null
         */
        protected $_urn = NULL;

        /**
         * Не знаю что это))
         *
         * @var int|null
         */
        protected $_firm_yes= NULL;

        /**
         * тип 1 - легковые, truck 2 грузовики
         *
         * @var int|null
         */
        protected $_type = NULL;

        /**
         * id автобренда к примеру vw=39
         *
         * @var int|null
         */
        protected $_mid = NULL;

        public static function factory(array $data = array())
        {
            return new self($data);
        }

        public function __construct(array $data = array())
        {
            $this->_data = $data;
        }

        public function getModelFirmName()
        {
            return ( $this->_model_firm_name !== NULL ) ? $this->_model_firm_name : $this->get_data_value('MODEL_FIRM_NAME');
        }

        public function getCountryName()
        {
            return ( $this->_country_name !== NULL ) ? $this->_country_name : $this->get_data_value('C_NAME');
        }

        public function getUrn()
        {
            return ( $this->_urn !== NULL ) ? $this->_urn : $this->get_data_value('URN');
        }

        public function getModelFirmNameRus()
        {
            return ( $this->_model_firm_name_rus !== NULL ) ? $this->_model_firm_name_rus : $this->get_data_value('MODEL_FIRM_NAME_RUS');
        }

        public function getFirmYes()
        {
            return ( $this->_firm_yes !== NULL ) ? $this->_firm_yes : $this->get_data_value('firm_yes');
        }

        public function getMId()
        {
            return ( $this->_mid !== NULL ) ? $this->_mid : $this->get_data_value('mid');
        }

        public function setUrn($urn)
        {
            $this->_urn = $urn;
            return $this;
        }

        public function setModelFirmName($model_firm_name)
        {
            $this->_model_firm_name = $model_firm_name;
            return $this;
        }

        public function setCountryName($country_name)
        {
            $this->_country_name = $country_name;
            return $this;
        }

        public function setModelFirmNameRus($model_firm_name_rus)
        {
            $this->_model_firm_name_rus = $model_firm_name_rus;
            return $this;
        }

        public function setFirmYes($firm_yes)
        {
            $this->_firm_yes = $firm_yes;
            return $this;
        }

        public function setType($type)
        {
            $this->_type = $type;
            return $this;
        }

        public function getType()
        {
            return $this->_type;
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

        /**
         * @return array|mixed
         */
        public function jsonSerialize()
        {
            $json = array(
                'urn'   =>  $this->getUrn(),
                'name'  =>  $this->getModelFirmName(),
                'country_name'  =>  $this->getCountryName(),
                'name_rus'  =>  $this->getModelFirmNameRus(),
            );

            if (!empty($type = $this->getType())) {
                $json['type'] = $type;
            }

            return $json;
        }

        /**
         * Cвязка ключей 'country_name' и 'C_NAME' в рамках перевода на АПИ
         *
         * @param array $json
         * @return $this
         */
        public static function jsonDeserialize(array $json)
        {
            return (new self())
                ->setUrn($json['urn'])
                ->setModelFirmName($json['name'])
                ->setCountryName($json['country_name'])
                ->setModelFirmNameRus($json['name_rus']);
        }

        /**
         * Возвращает идентификатор объекта
         *
         * @return mixed
         */
        public function get_identifier()
        {
            // если в одной коллекции мешаем и легковые и грузовые, ТО надо к volvo добавлять еще и тип, грузовой либо легковой
            if ($this->getType() !== NUll) {
                return $this->getUrn() . '_' .$this->getType();
            }

            return $this->getUrn();
        }
    }
}
