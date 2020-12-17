<?php

namespace Euroauto\Vehicle
{
    class Model implements \JsonSerializable, \Euroauto\Identifiable
    {
        /** @var null Идентификатор модели */
        protected $_id = NULL;

        /** @var null Наименование модели (Touareg, Polo (Sed RUS), Superb)*/
        protected $_name = NULL;

        /** @var null Года выпуска (2002-2010, 2011>) */
        protected $_years = NULL;

        /** @var null Наименование модели с годами (например, Touareg 2002-2010) */
        protected $_name_year = NULL;

        /** @var null URN для конкретной модели (например, touareg_2002-2010) */
        protected $_urn = NULL;

        /** @var null Идентификатор бренда */
        protected $_brand_id = NULL;

        /** @var null Наименование бренда (например, VW, Skoda) */
        protected $_brand_name = NULL;

        /** @var null URN для бренда (например, skoda) */
        protected $_brand_urn = NULL;

        /** @var null Идентификатор группы */
        protected $_group_id = NULL;

        /** @var null Наименование группы моделей (например, Touareg) */
        protected $_group_name = NULL;

        /** @var null URN для группы модели (например, superb) */
        protected $_group_urn = NULL;

        /** @var null Тип модели (легковая 0, грузовая 1) */
        protected $_type = NULL;

        /** @var bool Есть ли фото у модели */
        protected $_has_photo = FALSE;

        /** @var bool id модели*/
        protected $_model_id = FALSE;

        /** @var int Является ли модель популярной (Нет 0, Да 1) */
        protected $_popular = 0;

        /** @var int Кол-во обслуживаний модели в сети ЕА за последние 18 месяцев */
        protected $_rating = 0;

        protected $_data = array();

        public static function factory($id, array $data = array())
        {
            return new self($id, $data);
        }

        public function __construct($id, array $data = array())
        {
            $this->_id = $id;
            $this->_data = $data;

            $this
                ->set_name($this->get_data_value('MODEL_NAME'))
                ->set_name_year($this->get_data_value('MODEL_NAME_YEAR'))
                ->set_urn($this->get_data_value('URN'))
                ->set_type($this->get_data_value('MODEL_TYPE'))
                ->set_has_photo($this->get_data_value('model_photo', FALSE))
                ->set_brand_name($this->get_data_value('MODEL_FIRM_NAME'))
                ->set_brand_urn($this->get_data_value('ms3_URN'))
                ->set_group_name($this->get_data_value('m2_name'))
                ->set_group_urn($this->get_data_value('ms2_URN'))
                ->set_model_id($this->get_data_value('mid'))
                ->set_brand_id($this->get_data_value('mf_id'))
            ;
        }

        public function get_id()
        {
            return $this->_id;
        }

        /**
         * Наименование бренда
         * VW, Skoda
         * @deprecated
         * @return string|null
         */
        public function get_brand()
        {
            return $this->get_brand_name();
        }

        /**
         * Получение наименовани
         *
         * @return null
         */
        public function get_brand_name()
        {
            // На время перехода, у клиентов старые данные в сессиях
            return ( $this->_brand_name !== NULL ) ? $this->_brand_name : $this->get_data_value('MODEL_FIRM_NAME');
        }

        /**
         * Наименование бренда
         * @deprecated
         * @param $brand
         * @return $this
         */
        public function set_brand($brand)
        {
            return $this->set_brand_name($brand);
        }

        public function set_brand_name($brand_name)
        {
            $this->_brand_name = $brand_name;

            return $this;
        }

        /**
         * Наименование модели
         * Touareg, Polo (Sed RUS), Superb
         * @return string|null
         */
        public function get_name()
        {
            return ( $this->_name !== NULL ) ? $this->_name : $this->get_data_value('MODEL_NAME');
        }

        /**
         * id модели
         * @return int|null
         */
        public function get_model_id()
        {
            return ( $this->_model_id !== NULL ) ? $this->_model_id : $this->get_data_value('mid');
        }

        /**
         * Наименование модели
         * @param $name
         * @return $this
         */
        public function set_model_id($id)
        {
            $this->_model_id = $id;
            return $this;
        }

        /**
         * Наименование модели
         * @param $name
         * @return $this
         */
        public function set_name($name)
        {
            $this->_name = $name;
            return $this;
        }

        /**
         * Года выпуска
         * 2002-2010, 2011>
         * @return string|null
         */
        public function get_years()
        {
            return $this->_years;
        }

        /**
         * Года выпуска
         * 2002-2010, 2011>
         * @param $years
         * @return $this
         */
        public function set_years($years)
        {
            $this->_years = $years;
            return $this;
        }

        /**
         * Получение наименования с годами (например, Touareg 2002-2010)
         *
         * @return null
         */
        public function get_name_with_year()
        {
            return $this->get_name_year();
        }

        /**
         * Есть ли фото у данной модели
         *
         * @return bool
         */
        public function has_photo()
        {
            return $this->_has_photo;
        }

        /**
         * Установка флага "Есть фото"
         *
         * @param $has_photo
         * @return $this
         */
        public function set_has_photo($has_photo)
        {
            $this->_has_photo = !! $has_photo;

            return $this;
        }

        /**
         * @deprecated
         *
         * @return string
         */
        public function get_photo_url()
        {
            return 'http://img.euroauto.ru/photo/models/' . $this->get_id() . '/1.png';
        }

        /**
         * Получение типа модели (0 - легковая, 1 - грузовая)
         *
         * @return null
         */
        public function get_type()
        {
            return ( $this->_type !== NULL ) ? $this->_type : $this->get_data_value('MODEL_TYPE');
        }

        /**
         * Установка типа модели
         *
         * @param $type
         * @return $this
         */
        public function set_type($type)
        {
            $this->_type = $type;

            return $this;
        }

        /**
         * Получение наименования типа модели
         *
         * @return string
         */
        public function get_type_name()
        {
            $result = 'unknown';

            $types = array(
                0 => 'car',
                1 => 'truck',
            );

            if ( array_key_exists($this->get_type(), $types) )
            {
                $result = $types[$this->get_type()];
            }

            return $result;
        }

        /**
         * @deprecated
         * @return mixed
         */
        public function get_m2_name()
        {
            return $this->get_group_name();
        }

        /**
         * Получение urn для модели (например, touareg_2002-2010)
         *
         * @return null
         */
        public function get_urn()
        {
            // На время перехода, у клиентов старые данные в сессиях
            return ( $this->_urn !== NULL ) ? $this->_urn : $this->get_data_value('URN');
        }

        /**
         * @deprecated
         * @return array
         */
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

        /**
         * Возвращает url на каталог ea
         * @return string
         */
        public function get_ea_catalog_url()
        {
            return '/auto/cars/' . $this->get_brand_urn() . '/' . $this->get_group_urn() . '/' . $this->get_urn() . '/';
        }

        /**
         * Например, touareg
         * @deprecated
         *
         * @return mixed
         */
        public function get_model_urn()
        {
            return $this->get_group_urn();
        }

        /**
         * Например, vw
         * @deprecated
         *
         * @return mixed
         */
        public function get_firm_urn()
        {
            return $this->get_brand_urn();
        }

        /**
         * Получение urn бренда
         *
         * @return null
         */
        public function get_brand_urn()
        {
            // На время перехода, у клиентов старые данные в сессиях
            return ( $this->_brand_urn !== NULL ) ? $this->_brand_urn : $this->get_data_value('ms3_URN');
        }

        /**
         * Установка urn бренда
         *
         * @param $brand_urn
         * @return $this
         */
        public function set_brand_urn($brand_urn)
        {
            $this->_brand_urn = $brand_urn;

            return $this;
        }

        /**
         * Получение urn группы модели
         *
         * @return null
         */
        public function get_group_urn()
        {
            // На время перехода, у клиентов старые данные в сессиях
            return ( $this->_group_urn !== NULL ) ? $this->_group_urn : $this->get_data_value('ms2_URN');
        }

        /**
         * Установка urn группы модели
         *
         * @param $group_urn
         * @return $this
         */
        public function set_group_urn($group_urn)
        {
            $this->_group_urn = $group_urn;

            return $this;
        }

        /**
         * Установка urn модели
         *
         * @param $urn
         * @return $this
         */
        public function set_urn($urn)
        {
            $this->_urn = $urn;

            return $this;
        }

        /**
         * Получение наименования модели с годами
         *
         * @return null
         */
        public function get_name_year()
        {
            return ( $this->_name_year !== NULL ) ? $this->_name_year : $this->get_data_value('MODEL_NAME_YEAR');
        }

        /**
         * Установка наименования модели с годами
         *
         * @param $name_year
         * @return $this
         */
        public function set_name_year($name_year)
        {
            $this->_name_year = $name_year;

            return $this;
        }

        /**
         * Получение наименования группы
         *
         * @return null|string
         */
        public function get_group_name()
        {
            return $this->_group_name;
        }

        /**
         * Установка наименования группы
         *
         * @param $group_name
         * @return $this
         */
        public function set_group_name($group_name)
        {
            $this->_group_name = $group_name;

            return $this;
        }

        /**
         * Установка идентификатора бренда
         *
         * @param $brand_id
         * @return $this
         */
        public function set_brand_id($brand_id)
        {
            $this->_brand_id = $brand_id;

            return $this;
        }

        /**
         * Получение идентификатора бренда
         *
         * @return null
         */
        public function get_brand_id()
        {
            return $this->_brand_id;
        }

        /**
         * Установка идентификатора группы
         *
         * @param $group_id
         * @return $this
         */
        public function set_group_id($group_id)
        {
            $this->_group_id = $group_id;

            return $this;
        }

        /**
         * Получение идентификатора группы
         *
         * @return null
         */
        public function get_group_id()
        {
            return $this->_group_id;
        }

        /**
         * Возвращает идентификатор объекта
         *
         * @return mixed
         */
        public function get_identifier()
        {
            return $this->get_id();
        }


        /**
         * @Inheritdoc
         */
        function jsonSerialize()
        {
            return array(
                'id'    =>  (string) $this->get_id(),
                'brand_name' =>  $this->get_brand_name(),
                'name'  =>  $this->get_name(),
                'name_year' =>  $this->get_name_year(),
                'urn'   =>  $this->get_urn(),
                'brand_urn' =>  $this->get_brand_urn(),
                'group_urn' =>  $this->get_group_urn(),
                'type_name'  =>  $this->get_type_name(),
                'popular'  =>  $this->get_popular(),
                'rating'  =>  $this->get_rating(),
            );
        }

        /**
         * Формат данных из v1 api ('api/catalog/product/0-0-532-0/application')
         * Array(
         *     [id] => 251
         *     [brand_name] => VW
         *     [name] => Passat
         *     [name_year] => Passat [B3] 1988-1993
         *     [urn] => passat_b3_1988-1993
         *     [brand_urn] => vw
         *     [group_urn] => passat
         *     [type_name] => car
         * )
         *
         * @param array $arr
         * @return Model
         */
        public static function jsonDeserialize(array $arr)
        {
            $id = isset($arr['id']) ? intval($arr['id']) : 0;
            $model = new self($id);

            if (isset($arr['brand_name'])) {
                $model->set_brand_name($arr['brand_name']);
            }

            if (isset($arr['name'])) {
                $model->set_name($arr['name']);
            }

            if (isset($arr['name_year'])) {
                $model->set_years($arr['name_year']);
            }

            if (isset($arr['urn'])) {
                $model->set_urn($arr['urn']);
            }

            if (isset($arr['brand_urn'])) {
                $model->set_brand_urn($arr['brand_urn']);
            }

            if (isset($arr['group_urn'])) {
                $model->set_group_urn($arr['group_urn']);
            }

            if (isset($arr['type_name'])) {
                switch ($arr['type_name']) {
                    case 'car':   $model->set_type(0); break;
                    case 'truck': $model->set_type(1); break;
                }
            }

            if (isset($arr['popular'])) {
                $model->set_popular($arr['popular']);
            }

            if (isset($arr['rating'])) {
                $model->set_rating($arr['rating']);
            }

            return $model;
        }

        /**
         * Установка популярности модели
         *
         * @param $popular
         * @return $this
         */
        public function set_popular($popular)
        {
            $this->_popular = (int) $popular;

            return $this;
        }

        public function get_popular()
        {
            return $this->_popular;
        }

        /**
         * Является ли модель популярной
         *
         * @return bool
         */
        public function is_popular()
        {
            return $this->get_popular() > 0;
        }

        /**
         * Установка рейтинга модели (кол-во обслуживаний в сети EA за последние 18 мес.)
         *
         * @param $rating
         * @return $this
         */
        public function set_rating($rating)
        {
            $this->_rating = (int) $rating;

            return $this;
        }

        /**
         * Получение рейтинга модели
         *
         * @return int
         */
        public function get_rating()
        {
            return $this->_rating;
        }

        /**
         * Полное наименование модификации
         *
         * @return string - Например: "Kia Ceed 2012-2018"
         */
        public function get_brand_model_year()
        {
            return sprintf(
                '%s %s %s',
                $this->get_brand_name(),
                $this->get_name_with_year(),
                $this->get_years()
            );
        }

        /**
         * Для использования в Twig collection|join(', ')
         *
         * @return string
         */
        public function __toString()
        {
            return $this->get_brand_model_year();
        }
    }
}

/*array(12) {
    ["MODEL_ID"]=>
    string(3) "746"
    ["MODEL_FIRM_NAME"]=>
    string(2) "VW"
    ["MODEL_NAME"]=>
    string(7) "Touareg"
    ["MODEL_NAME_YEAR"]=>
    string(17) "Touareg 2002-2010"
    ["MODEL_TYPE"]=>
    string(1) "0"
    ["model_photo"]=>
    string(1) "1"
    ["URN"]=>
    string(17) "touareg_2002-2010"
    ["FIRM_URN"]=>
    string(2) "vw"
    ["ms2_URN"]=>
    string(7) "touareg"
    ["ms3_URN"]=>
    string(2) "vw"
    ["m2_name"]=>
    string(7) "Touareg"
    ["m3_name"]=>
    string(2) "VW"
  }*/
