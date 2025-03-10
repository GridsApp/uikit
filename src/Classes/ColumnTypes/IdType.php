<?php

namespace twa\uikit\Classes\ColumnTypes;

class IdType extends DefaultType
{
    public function html($parameters = []){
        return $this->input;
    }
}
