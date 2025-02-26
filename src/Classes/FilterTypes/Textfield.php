<?php

namespace twa\uikit\Classes\FilterTypes;



class Textfield extends FilterType
{


    public $field_type = \twa\uikit\FieldTypes\Textfield::class;

 
    public function options()
    {

        return [
            ['value' => 'contains', 'label' => 'Contains', 'active' => true],
            ['value' => 'equals', 'label' => 'Equals', 'active' => false],
            ['value' => 'starts_with', 'label' => 'Starts With', 'active' => false],
        ];
    }
}
