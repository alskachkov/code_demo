<?php

namespace Euroauto\Vehicle\Model;

use Euroauto\Vehicle\Model;

class Collection extends \Euroauto\Collection
{
    /**
     * @param Model $model
     * @return $this
     */
    public function add(Model $model)
    {
        return parent::_add($model);
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function remove(Model $model)
    {
        return parent::_remove($model);
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function has(Model $model)
    {
        return parent::_has($model);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array_values($this->_items);
    }

    /**
     * Топ 10 самых популярных из рассчета кол-ва обслуживаний в сети ЕА за последние 1,5 года
     *
     * @param int $limit
     * @return static
     */
    public function getTopPopular($limit = 10)
    {
        $collection = clone $this;

        // Сортировка по популярности
        $collection->_sort(
            [
               'get_popular' => false,
               'get_rating' => false,
            ]
        );

        return $collection->slice($limit);
    }
}