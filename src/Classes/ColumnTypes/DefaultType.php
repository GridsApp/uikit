<?php

namespace twa\uikit\Classes\ColumnTypes;


class DefaultType
{

    public $input;
    public $row;

    public function __construct($input , $row)
    {


        $this->row = $row;
        $this->input = $input;

    }

    public function html($parameters = []){

        if(empty($this->input)){
            return "-";
        }

        return $this->input;
    }

}

