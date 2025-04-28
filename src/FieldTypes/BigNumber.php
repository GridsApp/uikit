<?php

namespace twa\uikit\FieldTypes;




class BigNumber extends FieldType
{

    public function component()
    {
        return "elements.big-number";
    }


    public function filterType(){

        return \twa\uikit\Classes\FilterTypes\Number::class;
    }

    public function db(&$table){
        $table->bigInteger($this->field['name'])->nullable();
    }

}
