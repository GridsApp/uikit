<?php

namespace twa\uikit\Classes\ColumnTypes;


class DefaultType
{

    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function html(){
        return $this->input;
    }

}

