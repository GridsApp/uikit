<?php
use Livewire\Livewire;


function get_field_modal($field)
{
    return str_replace("{name}", $field['name'], $field['livewire']['wire:model'] ?? ($field['livewire']['wire:model.live'] ?? ""));
}


    function field($field, $container = null){

        if (is_string($field)) {
            $field = config('fields.' . $field);
        }




        if (!$field) {
            return null;
        }


        if (!(isset($field['livewire']) && $field['livewire'])) {
            $field['livewire'] = [
                "wire:model" => $field['name']
            ];
        } else {
            $field['livewire'] = collect($field['livewire'])->map(function ($value) use ($field) {
                return str_replace('{name}', $field['name'], $value);
            })->toArray();
        }

        if (!isset($field['index'])) {
            $field['index'] = 999;
        }


        $params = [
            "info" => $field,
            ...$field['livewire']
        ];

        if (isset($field['translatable']) && $field['translatable']) {

            $render = view("UIKitView::components.form.language", ['info' => $field]);

            $container = $field['container'] ?? $container;

            if ($container) {
                return "<div class='" . $container . "'>" . $render . "</div>";
            } else {
                return $render;
            }
        }



        $path = (new $field['type']($field))->component();

        $render = Livewire::mount($path, $params, "component_" . uniqid());

        $container = $field['container'] ?? $container;

        if ($container) {
            return "<div class='" . $container . "'>" . $render . "</div>";
        }

        return $render;
    }


    function apply_condition(&$rows , $condition){
         
        $condition['operand'] = $condition['operand'] ?? '=';

        switch ($condition['type']) {
            case 'having':
                $rows->having($condition['column'], $condition['operand'], $condition['value']);
                break;

            case 'whereIn':
                $rows->whereIn($condition['column'], $condition['value']);
                break;

            case 'whereNotIn':
                $rows->whereNotIn($condition['column'], $condition['value']);
                break;

            case 'whereNotNull':

                $rows->whereNotNull($condition['column']);
                break;
            case 'like':

                $rows->where($condition['column'], 'LIKE', '%' . $condition['value'] . '%');
                break;

            case 'whereNull':
               
                $rows->whereNull($condition['column']);
                break;

            default:


         

                $rows->where($condition['column'], $condition['operand'], $condition['value']);
            
                break;
        }

    }


    function get_assets(){
        return collect(json_decode(file_get_contents(__DIR__.'/../../dist/.vite/manifest.json'), true))->map(function($item){
            return "vendor/twa/uikit/dist/".$item['file'];
        })->values()->toArray();
    }