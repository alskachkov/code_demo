<?php

namespace Euroauto\Vehicle\GroupModel;

use Euroauto\Vehicle\GroupModel;

class Collection extends \Euroauto\Collection
{
    /**
     * @param GroupModel $group_model
     * @return $this
     */
    public function add(GroupModel $group_model)
    {
        return parent::_add($group_model);
    }

    /**
     * @param GroupModel $group_model
     * @return $this
     */
    public function remove(GroupModel $group_model)
    {
        return parent::_remove($group_model);
    }

    /**
     * @param GroupModel $group_model
     * @return bool
     */
    public function has(GroupModel $group_model)
    {
        return parent::_has($group_model);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array_values($this->_items);
    }
}