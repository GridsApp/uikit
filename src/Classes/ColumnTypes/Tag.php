<?php

namespace twa\uikit\Classes\ColumnTypes;


class Tag extends DefaultType
{


    public function html()
    {

        if (!is_array($this->input)) {
            $this->input = [$this->input];
        }

        $html = "<div class='twa-table-td-select'><span>{$this->input[0]}</span></div>";


        return $html;
    }
}
