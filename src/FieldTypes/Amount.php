<?php


namespace twa\uikit\FieldTypes;




class Amount extends FieldType
{


    public function component()
    {
        return "elements.amount";
    }

    public function filterType()
    {

        return \twa\uikit\Classes\FilterTypes\Number::class;
    }
}
