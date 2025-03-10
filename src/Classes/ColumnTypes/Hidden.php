<?php
namespace twa\uikit\Classes\ColumnTypes;

class Hidden extends DefaultType
{
    public function html($parameters = [])
    {
        return '<div class="hidden">$this->input</div>';
    }
}
