<?php
namespace twa\uikit\Classes\ColumnTypes;

class CopyableType extends DefaultType
{
    public function html()
    {
        return '
        <div x-data="{ copied: false }">
            <span 
                  x-on:click="navigator.clipboard.writeText(\'' . $this->input . '\'); copied = true; setTimeout(() => copied = false, 700)"
                  :class="{ \'copied\': copied }"
                  class="copiable"
                  data-text="' . $this->input . '">
                ' . $this->input . '
            </span>
        </div>
        ';
    }
}
