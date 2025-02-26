<?php

namespace twa\uikit\Classes\ColumnTypes;


class Image extends DefaultType
{

    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function html(){
        

        if(!(isset($this->input) && $this->input)){
            return "<div class='twa-table-td-image placeholder'><i class='fa-duotone fa-solid fa-image'></i></div>";
        }
        return "<div class='twa-table-td-image'><img class='td-image' src='$this->input'></div>";

    }

}

