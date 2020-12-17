<?php

/**
 * Список моделей бренда (структура)

 * [mid] => 96
 * [MODEL_NAME_YEAR] => 100/200
 * [URN] => 100-200
 * [MODEL_TYPE] => 0
 * [MODEL_FIRM_NAME] => Audi
 * [model_photo] => 18
 *
 */

namespace Euroauto\Vehicle
{
    class GroupModel implements \JsonSerializable, \Euroauto\Identifiable
    {
        protected $_data = array();

        /**
         * @var string|null
         */
        protected $_mid = NULL;

        /**
         * @var string|null
         */
        protected $_model_name_year = NULL;

        /**
         * @var string|null
         */
        protected $_urn = NULL;

        /**
         * @var string|null
         */
        protected $_model_type = NULL;

        /**
         * Audi
         * @var string|null
         */
        protected $_model_firm_name = NULL;

        /**
         * ауди
         * @var string|null
         */
        protected $_model_firm_name_rus = NULL;

        /**
         * @var string|null
         */
        protected $_model_photo = NULL;

        /**
         * @var int|null
         */
        protected $_firm_id = NULL;

        public static function factory(array $data = array())
        {
            return new self($data);
        }

        public function __construct(array $data = array())
        {
            $this->_data = $data;
        }

        public function getMid()
        {
            return ( $this->_mid !== NULL ) ? $this->_mid : $this->get_data_value('mid');
        }

        public function getFirmUrn()
        {
            return $this->get_data_value('FIRM_URN');
        }

        public function getModelNameYear()
        {
            return ( $this->_model_name_year !== NULL ) ? $this->_model_name_year : $this->get_data_value('MODEL_NAME_YEAR');
        }

        public function getModelFirmName()
        {
            return ( $this->_model_firm_name !== NULL ) ? $this->_model_firm_name : $this->get_data_value('MODEL_FIRM_NAME');
        }

        public function getModelPhoto()
        {
            return ( $this->_model_photo !== NULL ) ? $this->_model_photo : $this->get_data_value('model_photo');
        }

        /**
         * id записи названия фирмы
         * 
         * @return int|mixed|null
         */
        public function getFirmId()
        {
            return ( $this->_firm_id !== NULL ) ? $this->_firm_id : $this->get_data_value('fid');
        }

        public function getModelType()
        {
            return ( $this->_model_type !== NULL ) ? $this->_model_type : $this->get_data_value('MODEL_TYPE');
        }

        public function getUrn()
        {
            return ( $this->_urn !== NULL ) ? $this->_urn : $this->get_data_value('URN');
        }

        public function setModelFirmName($model_firm_name)
        {
            $this->_model_firm_name = $model_firm_name;
            return $this;
        }

        /**
         * Название марки машины на русском
         *
         * @return mixed|null|string
         */
        public function getModelFirmNameRus()
        {
            return ( $this->_model_firm_name_rus !== NULL ) ? $this->_model_firm_name_rus : $this->get_data_value('MODEL_FIRM_NAME_RUS');
        }

        public function setMid($mid)
        {
            $this->_mid = $mid;
            return $this;
        }

        public function setModelNameYear($model_name_year)
        {
            $this->_model_name_year = $model_name_year;
            return $this;
        }

        public function setModelPhoto($model_photo)
        {
            $this->_model_photo = $model_photo;
            return $this;
        }

        public function setModelType($model_type)
        {
            $this->_model_type = $model_type;
            return $this;
        }

        public function setUrn($urn)
        {
            $this->_urn = $urn;
            return $this;
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

        public function getFirmUrnByModelFirmName()
        {
            return mb_strtolower(str_replace(' ', '_', $this->getModelFirmName()));
        }

        /**
         * @Inheritdoc
         */
        function jsonSerialize()
        {
            return array(
                'id'    =>  $this->getMid(),
                'name'  =>  $this->getModelNameYear(),
                'urn'   =>  $this->getUrn(),
                'type'  =>  $this->getModelType(),
                'brand_name'    =>  $this->getModelFirmName(),
                'brand_urn' =>  $this->getFirmUrn(),
                'model_photo_id'    =>  $this->getModelPhoto(),
            );
        }

        /**
         * Возвращает идентификатор объекта
         *
         * @return mixed
         */
        public function get_identifier()
        {
            return $this->getMid();
        }
    }
}
