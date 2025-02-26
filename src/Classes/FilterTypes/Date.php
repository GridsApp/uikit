<?php

namespace twa\uikit\Classes\FilterTypes;

class Date extends FilterType
{


    public $field_type = \twa\uikit\FieldTypes\Date::class;


    public function options()
    {
        return [
            ['value' => 'equals', 'label' => 'Equals','active' => true],
            ['value' => 'is_greater', 'label' => 'Is Greater','active' => false],
            ['value' => 'is_greater_or_equal', 'label' => 'Is Greater Or Equal','active' => false],
            ['value' => 'is_less', 'label' => 'Is Less','active' => false],
            ['value' => 'is_less_or_equal', 'label' => 'Is Less Or Equal','active' => false],
            ['value' => 'between', 'label' => 'Between','active' => false],
 
        ];
    }
}
