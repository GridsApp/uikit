<?php

namespace twa\uikit\Classes\ColumnTypes;


class Colorpicker extends DefaultType
{


    public function html($parameters = [])
    {

      

        // if (!empty($this->input[0])) {
            return "<div class='twa-table-td-color'><div class='td-color' style='background-color:" .$this->input . "' ></div><div class='text'>" .$this->input . "</div></div>";

           
        // }
        return '';
    }
}
