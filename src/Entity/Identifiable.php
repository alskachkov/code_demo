<?php

namespace Euroauto;

interface Identifiable
{
    /**
     * Возвращает идентификатор объекта
     *
     * @return mixed
     */
    public function get_identifier();
}