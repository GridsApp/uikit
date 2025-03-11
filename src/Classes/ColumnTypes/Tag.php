<?php

namespace twa\uikit\Classes\ColumnTypes;


class Tag extends DefaultType
{


    public function html($parameters = [])
    {

        if (!is_array($this->input)) {
            $this->input = [$this->input];
        }

        if (!empty($this->input[0])) {
            return "<div class='twa-table-td-select'><span>{$this->input[0]}</span></div>";
        }
        return ''; 
    }
}
