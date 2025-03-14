<?php

namespace twa\uikit\Classes\ColumnTypes;


class PolymorphicType extends DefaultType
{


    public function html($parameters = [])
    {

        if(!str($this->input)->contains('$$$')){
            return $this->input;
        }


        $input = str($this->input)->explode('$$$');

        




        if (!empty($input[1])) {
            return "<div class='twa-table-td-select !ring-0' style='background: transparent; color: ".$input[0]." ; border: 1px solid ".$input[0]."'><span>{$input[1]}</span></div>";
        }
        return ''; 
    }
}
