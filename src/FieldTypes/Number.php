<?php

namespace twa\uikit\FieldTypes;




class Number extends FieldType
{

    public function component()
    {
        return "elements.number";
    }

    public function db(&$table){
        $table->double($this->field['name'])->nullable();
    }

}
