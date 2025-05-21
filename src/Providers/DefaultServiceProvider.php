<?php

namespace twa\uikit\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use twa\uikit\Support\Blade\Directives;

class DefaultServiceProvider extends ServiceProvider{


    public function boot(){
        Livewire::component('elements.colorpicker',\twa\uikit\Elements\Colorpicker::class);
        Livewire::component('elements.big-number',\twa\uikit\Elements\BigNumber::class);
        Livewire::component('elements.date',\twa\uikit\Elements\Date::class);
        Livewire::component('elements.datetime',\twa\uikit\Elements\Datetime::class);
        Livewire::component('elements.editor',\twa\uikit\Elements\Editor::class);
        Livewire::component('elements.email',\twa\uikit\Elements\Email::class);
        Livewire::component('elements.file-upload',\twa\uikit\Elements\FileUpload::class);
        Livewire::component('elements.hidden',\twa\uikit\Elements\Hidden::class);
        Livewire::component('elements.language',\twa\uikit\Elements\Language::class);
        Livewire::component('elements.number',\twa\uikit\Elements\Number::class);
        Livewire::component('elements.password',\twa\uikit\Elements\Password::class);
        Livewire::component('elements.select',\twa\uikit\Elements\Select::class);
        Livewire::component('elements.textarea',\twa\uikit\Elements\Textarea::class);
        Livewire::component('elements.textfield',\twa\uikit\Elements\Textfield::class);
        Livewire::component('elements.toggle',\twa\uikit\Elements\Toggle::class);
        Livewire::component('elements.positive-number', \twa\uikit\Elements\PositiveNumber::class);
        Livewire::component('elements.amount', \twa\uikit\Elements\Amount::class);
        Livewire::component('components.table',\twa\uikit\Components\Table::class);
        Livewire::component('components.table-grid',\twa\uikit\Components\TableGrid::class);


        Directives::register();



        // $this->loadScriptAndStyles();

        // $this->publishes([
        //     __DIR__.'/../Config/fields.php' => config_path('fields.php'),
        // ], 'laravel-assets');

    }

    public function register(){
        include_once(__DIR__.'/../Helpers/default.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views/' , 'UIKitView');

        $this->loadRoutesFrom(__DIR__.'/../Routes/console.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->app->singleton('field-assets', function () {
            return get_assets();
        });

    }




}
