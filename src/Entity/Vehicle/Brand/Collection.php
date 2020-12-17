<?php

namespace Euroauto\Vehicle\Brand;

use Euroauto\Vehicle\Brand;

class Collection extends \Euroauto\Collection
{
    /**
     * @param Brand $brand
     *
     * @return $this
     */
    public function add(Brand $brand)
    {
        return parent::_add($brand);
    }

    /**
     * @param Brand $brand
     *
     * @return $this
     */
    public function remove(Brand $brand)
    {
        return parent::_remove($brand);
    }

    /**
     * @param Brand $brand
     *
     * @return bool
     */
    public function has(Brand $brand)
    {
        return parent::_has($brand);
    }

    /**
     * сортировка по алфавиту и по типу, гузовой, легковой
     *
     * @return $this
     */
    public function sort()
    {

        uasort($this->_items, function(Brand $a, Brand $b){

            if ($a->getModelFirmName() < $b->getModelFirmName()) {
                return -1;
            }

            if ($a->getModelFirmName() > $b->getModelFirmName()) {
                return 1;
            }

            if ($a->getModelFirmName() == $b->getModelFirmName()) {
                if ($a->getType() == $b->getType()) return 0;
                return ($a->getType() < $b->getType()) ? -1 : 1;
            }
        });

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array_values($this->_items);
    }
}