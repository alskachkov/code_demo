<?php

namespace Euroauto;

use Euroauto\Identifiable;

abstract class Collection implements \JsonSerializable, \Countable, \IteratorAggregate
{
    /** @var array Identifiable[] */
    protected $_items = array();

    /**
     * Фабричный метод
     *
     * @return static
     */
    public static function factory()
    {
        return new static();
    }

    /**
     * @param Identifiable $item
     * @return $this
     */
    protected function _add(Identifiable $item)
    {
        if ( ! $this->_has($item) )
        {
            $this->_items[$item->get_identifier()] = $item;
        }

        return $this;
    }

    /**
     * @param Identifiable $item
     * @return $this
     */
    protected function _remove(Identifiable $item)
    {
        unset($this->_items[$item->get_identifier()]);

        return $this;
    }

    /**
     * @param Identifiable $item
     * @return bool
     */
    protected function _has(Identifiable $item)
    {
        return array_key_exists($item->get_identifier(), $this->_items);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->_items;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_items);
    }

    /**
     * Получение первого элемента
     *
     * @return null
     */
    public function get_first_item()
    {
        return reset($this->_items);
    }

    /**
     * Возвращает элемент по ключу
     *
     * @param $id
     * @return Identifiable|null
     */
    public function get_by_id($id)
    {
        return (isset($this->_items[$id])) ? $this->_items[$id] : NULL;
    }

    /**
     * Получение идентификаторов всех элементов
     *
     * @return array
     */
    public function get_ids()
    {
        return array_keys($this->_items);
    }

    /**
     * Сортировка по идентификатору
     *
     * @param bool $asc
     * @return $this
     */
    public function sort_by_id($asc = true)
    {
        $this->_sort(array('get_identifier' => $asc));

        return $this;
    }

    /**
     * Сортировочка
     *
     * @param array $sortings
     * @return $this
     */
    protected function _sort(array $sortings)
    {
        if ( count($sortings) > 0 )
        {
            uasort($this->_items, function (\Euroauto\Identifiable $a, \Euroauto\Identifiable $b) use ($sortings)
            {
                $sort_types = array_keys($sortings);
                $sort_directions = array_values($sortings);

                if ( count($sort_types) > 1 )
                {
                    for ( $i = 1; $i < count($sort_types); $i++ )
                    {
                        if ( $a->{$sort_types[$i - 1]}() == $b->{$sort_types[$i - 1]}() )
                        {
                            if ( $a->{$sort_types[$i]}() < $b->{$sort_types[$i]}() )
                            {
                                return ($sort_directions[$i]) ? -1 : 1;
                            }
                            else
                            {
                                return ($sort_directions[$i]) ? 1 : -1;
                            }
                        }
                    }
                }

                if ( $a->{$sort_types[0]}() < $b->{$sort_types[0]}() )
                {
                    return ($sort_directions[0]) ? -1 : 1;
                }
                else
                {
                    return ($sort_directions[0]) ? 1 : -1;
                }
            });
        }

        return $this;
    }

    /**
     * Реализация limit и offset
     *
     * @param $limit
     * @param int $offset
     * @return static
     */
    public function slice($limit = NULL, $offset = 0)
    {
        $collection = new static();

        $items = array_slice($this->_items, $offset, $limit);

        foreach ( $items as $item )
        {
            $collection->add($item);
        }

        return $collection;
    }
}