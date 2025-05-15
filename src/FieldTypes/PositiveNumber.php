<?php

namespace twa\uikit\FieldTypes;




class PositiveNumber extends FieldType
{

    public function component()
    {
        return "elements.positive-number";
    }


    public function filterType()
    {

        return \twa\uikit\Classes\FilterTypes\Number::class;
    }

    public function db(&$table)
    {
        $table->double($this->field['name'])->nullable();
    }
}
