<?php

namespace twa\uikit\Classes\ColumnTypes;


class EnumType extends DefaultType
{


    public function html($parameters = [])
    {


        $enum = $parameters['enum'];


        $type = $this->input;

        try {
             $selectedEnum = $enum::from($type);
        } catch (\Throwable $th) {
                $selectedEnum = null;
        }

        if(!$selectedEnum){
            return $type;
        }

        return "<div class='twa-table-td-select min-w-40 !px-2 !py-[6px] flex justify-center ' style='background-color:".$selectedEnum->BgColor().";color: ".$selectedEnum->TextColor()." ;' ><span>{$selectedEnum->label()}</span></div>";
    }
}
