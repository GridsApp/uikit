<?php

namespace twa\uikit\Classes\ColumnTypes;


class EnumType extends DefaultType
{


    public function html($parameters = [])
    {


        $enum = $parameters['enum'];

        $enumType = $parameters['type'] ?? null;

      

        $type = $this->input;

        try {
             $selectedEnum = $enum::from($type);
        } catch (\Throwable $th) {
                $selectedEnum = null;
        }

        if(!$selectedEnum){
            return $type;
        }

        if($enumType == 'text'){
            return $selectedEnum->label();
        }

        return "<div class='twa-table-td-select w-fit !px-4 !py-[6px] flex justify-center ' style='background-color:".$selectedEnum->BgColor().";color: ".$selectedEnum->TextColor()." ;' ><span>{$selectedEnum->label()}</span></div>";
    }
}
