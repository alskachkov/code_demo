<?php

namespace Euroauto\Vehicle
{
    class Collection implements \Countable, \Iterator
    {
        /** @var \Euroauto\Vehicle[] */
        protected $_vehicles = array();

        public function add(\Euroauto\Vehicle $vehicle)
        {
            // если есть, то сначала удаяем
            if ($this->has($vehicle) )
            {
                $this->remove($vehicle);
            }
            // добавляем в любом случае (для сортировки, последняя введенная всегда сверу, сортируем просто по ключам массива)
            $this->_vehicles[] = $vehicle;

            return $this;
        }

        public function remove(\Euroauto\Vehicle $vehicle)
        {
            foreach ( $this->_vehicles as $index => $ex_vehicle )
            {
                if ( $ex_vehicle->get_hash() === $vehicle->get_hash() )
                {
                    unset($this->_vehicles[$index]);
                }
            }

            return $this;
        }

        public function has(\Euroauto\Vehicle $vehicle)
        {
            $result = FALSE;

            foreach ( $this->_vehicles as $ex_vehicle )
            {
                if ( $ex_vehicle->get_hash() === $vehicle->get_hash() )
                {
                    $result = TRUE;
                }
            }

            return $result;
        }

        /**
         * Фильтрует ТС у которых есть VIN
         *
         * @return Collection
         */
        public function filter_has_vin()
        {
            $collection = new self();

            foreach ($this->_vehicles as $vehicle)
            {
                if (!empty($vehicle->get_vin()))
                {
                    $collection->add($vehicle);
                }
            }

            return $collection;
        }

        /**
         * Фильтрация по наличию определенной модели
         * 
         * @return Collection
         */
        public function filter_model_exist()
        {
            $collection = new self();

            foreach ( $this->_vehicles as $vehicle )
            {
                if ( $vehicle->get_model() !== NULL )
                {
                    $collection->add($vehicle);
                }
            }

            return $collection;
        }

        /**
         * Фильтрация по статусу
         * @param $status
         * @return Collection
         */
        public function filter_active($status = TRUE)
        {
            $collection = new self();

            foreach ( $this->_vehicles as $vehicle )
            {
                if ( $vehicle->get_active() == $status )
                {
                    $collection->add($vehicle);
                }
            }

            return $collection;
        }
        
        /**
         * Сортировка в обратном порядке
         * 
         * @return $this
         */
        public function sort_reverse()
        {
            krsort($this->_vehicles);

            return $this;
        }

        public function count()
        {
            return count($this->_vehicles);
        }

        public function as_array()
        {
            return $this->_vehicles;
        }

        /**
         * Возвращает ТС по model_id
         *
         * @param $model_id
         * @return \Euroauto\Vehicle|null
         */
        public function get_vehicle_by_model_id($model_id)
        {
            foreach ( $this->_vehicles as $vehicle )
            {
                $vehicle_model = $vehicle->get_model();

                if ( $vehicle_model !== NULL AND $vehicle_model->get_id() == $model_id )
                {
                    return $vehicle;
                }
            }

            return NULL;
        }

        public function rewind()
        {
            return reset($this->_vehicles);
        }

        public function current()
        {
            return current($this->_vehicles);
        }

        public function key()
        {
            return key($this->_vehicles);
        }

        public function next()
        {
            return next($this->_vehicles);
        }

        public function valid()
        {
            $key = $this->key();
            return ($key !== NULL AND $key !== FALSE);
        }
    }
}