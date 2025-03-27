<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;


function get_field_modal($field)
{
    return str_replace("{name}", $field['name'], $field['livewire']['wire:model'] ?? ($field['livewire']['wire:model.live'] ?? ""));
}



function update_conditions($conditions)
{
    $field_permissions = session('field_permissions', []);

    // dd($conditions ,$field_permissions);

    if (empty($field_permissions)) {
        return [];
    }

    if (!is_array($field_permissions)) {
        $field_permissions = [];
    }

    $array_keys = array_keys($field_permissions);


    $array_keys =  array_map(function ($item) {
        return "{" . $item . "}";
    }, $array_keys);


    $array_values = array_values($field_permissions);




    $updated_conditions = [];
    // if (empty($updated_conditions)) {
    //     unset($conditions); 
    // }
    foreach ($conditions ?? [] as $condition) {
        if ($condition['value'] !== null) {
            $condition['value'] = str_replace($array_keys, $array_values, $condition['value']);
            $updated_conditions[] = $condition;
        }
    }

    return $updated_conditions;
}



function field($field, $container = null)
{

    if (is_string($field)) {
        $field = config('fields.' . $field);
    }


    $updated_conditions = update_conditions($field['options']['conditions'] ?? []);



    if (count($updated_conditions) > 0) {
        $field['options']['conditions'] = $updated_conditions;
    } else {
        $field['options']['conditions'] = [];
    }



    // dd($field_permissions);


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

        $container = $container ?? ($field['container'] ?? null);

        if ($container) {
            return "<div class='" . $container . "'>" . $render . "</div>";
        } else {
            return $render;
        }
    }



    $path = (new $field['type']($field))->component();

    $render = Livewire::mount($path, $params, "component_" . uniqid());

    $container = $container ?? ($field['container'] ?? null);

    if ($container) {
        return "<div class='" . $container . "'>" . $render . "</div>";
    }

    return $render;
}


function apply_condition(&$rows, $condition)
{

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


function get_assets()
{
    return collect(json_decode(file_get_contents(__DIR__ . '/../../dist/.vite/manifest.json'), true))->map(function ($item) {
        return "vendor/twa/uikit/dist/" . $item['file'];
    })->values()->toArray();
}

if (!function_exists('get_menu')) {
    function get_menu()
    {
        $menu = collect(config('menu'));

        if (!$menu) {
            return null;
        }
        $menu = $menu->map(function ($item) {
            return process_menu_item($item);
        });

        return $menu;
    }
}

if (!function_exists('process_menu_item')) {
    function process_menu_item($item)
    {
       
        if (isset($item['link'])) {
    

      
            $item['link'] = get_route_object_link($item['link']);

           
            
        }

       
        if (isset($item['children']) && is_array($item['children'])) {
           
            $item['children'] = array_map('process_menu_item', $item['children']);
        }

        return $item;
    }
}
