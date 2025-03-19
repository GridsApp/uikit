<?php

namespace twa\uikit\FieldTypes;


class Id extends FieldType
{

    public function component()
    {


   
        return "elements.id";
    }
    
    public function columnType()
    {
  
    
        return \twa\uikit\Classes\ColumnTypes\IdType::class;
    }



}
