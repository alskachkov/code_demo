<?php

namespace Euroauto
{

    /**
     * Class Vehicle
     * @package Euroauto
     */
    class Vehicle implements \JsonSerializable
    {
        protected $_id = NULL;
        protected $_vin = NULL;

        /**
         * @var \Euroauto\Vehicle\Model|null
         */
        protected $_model = NULL;

        /**
         * скрыто ли авто у пользователя (при удалении мы не удаляем запись, а меняем active в false)
         * @var null
         */
        protected $_active = NULL;

        /**
         * ну, а что делать? пока запихнул сюда (это id строки в табице person_vehicle понадобилась для дальнейшей работы с API, а именно выставление статуса astive)
         * @var null
         */
        protected $_id_person_vehicle_row = NULL;

        /**
         * @return Vehicle
         */
        public static function factory()
        {
            return new self();
        }

        /**
         * Получение идентификатора ТС
         *
         * @return null
         */
        public function get_id()
        {
            return $this->_id;
        }

        /**
         *
         * Статус тарнспортного средства (при удалении мы не удаляем запись, а меняем active в false)
         *
         * @return null
         */
        public function get_active()
        {
            return $this->_active;
        }

        /**
         *
         * Статус тарнспортного средства (при удалении мы не удаляем запись, а меняем active в false)
         *
         * @return null
         */
        public function set_active($active)
        {
            $this->_active = $active;
            return $this;
        }

        /**
         *
         * Статус тарнспортного средства (при удалении мы не удаляем запись, а меняем active в false)
         *
         * @return null
         */
        public function get_id_person_vehicle_row()
        {
            return $this->_id_person_vehicle_row;
        }

        /**
         *
         * Статус тарнспортного средства (при удалении мы не удаляем запись, а меняем active в false)
         * @param $id_person_vehicle_row
         * @return null
         */
        public function set_id_person_vehicle_row($id_person_vehicle_row)
        {
            $this->_id_person_vehicle_row = $id_person_vehicle_row;
            return $this;
        }

        /**
         * Установка идентификатора ТС
         *
         * @param $id
         * @return $this
         */
        public function set_id($id)
        {
            $this->_id = $id;
            return $this;
        }

        /**
         * Получить VIN
         *
         * @return null
         */
        public function get_vin()
        {
            return mb_strtolower(trim($this->_vin));
        }

        /**
         * Установить VIN
         *
         * @param $vin
         * @return $this
         */
        public function set_vin($vin)
        {
            $this->_vin = mb_strtolower($vin);
            return $this;
        }

        /**
         * Получение уникального id авто
         *
         * @return null
         */
        public function get_hash()
        {

            $hash = $this->get_vin();
            
            return $hash;
        }

        /**
         * Получение модели ТС
         * @return \Euroauto\Vehicle\Model|null
         */
        public function get_model()
        {
            return $this->_model;
        }

        /**
         * Установка модели ТС
         * @param Vehicle\Model $model
         *
         * @return $this
         */
        public function set_model(\Euroauto\Vehicle\Model $model)
        {
            $this->_model = $model;
            return $this;
        }

        /**
         * @inheritdoc
         *
         * @return mixed|void
         */
        public function jsonSerialize()
        {
            return array(
                'id'    =>  $this->get_id(),
                'vin' =>  $this->get_vin(),
                'hash'  =>  $this->get_hash(),
                'model' => $this->get_model(),
            );
        }
    }
}