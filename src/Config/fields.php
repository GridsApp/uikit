<?php
return[
    "email" => [
        'id' => uniqid(),
        'livewire' => [
            'wire:model' => 'form.{name}',
        ],
        'type' =>\twa\uikit\FieldTypes\Textfield::class,
        'label' => 'Email',
        'placeholder' => 'Email',
        'name' => 'email',
    ],
    "image" => [

        'id' => uniqid(),
        'livewire' => [
            'wire:model' => 'form.{name}',
        ],
        'multiple' => false,
        'aspect_ratio' => '1',
        'type' =>\twa\uikit\FieldTypes\FileUpload::class,
        'label' => 'Logo',
        'placeholder' => 'Upload Logo',
        'name' => 'image',
    ],
];