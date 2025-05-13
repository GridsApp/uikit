<?php

namespace twa\uikit\Support\Blade;

use Illuminate\Support\Facades\Blade;
use twa\uikit\Facades\TWAUIKit as Facade;

class Directives
{
    /**
     * Register the Blade directives.
     */
    public static function register(): void
    {


        Blade::directive('TWAUIKitScript', fn (): string => Facade::directives()->script());

        Blade::directive('TWAUIKitStyle', fn (mixed $expression): string => Facade::directives()->style());

    }

    /**
     * Get the HTML that represents the script load.
     */
    public function script(): string
    {

        $manifest = $this->manifest('src/Resources/js/app.js');
        $js = $manifest['file'];

        $html = $this->format($js);



        return $html;
    }

    /**
     * Get the HTML that represents the style load.
     */
    public function style(): string
    {
        $manifest = $this->manifest('src/Resources/scss/app.scss');
        $css = $manifest['file'];
        $html = $this->format($css);

        return $html;
    }

    /**
     * Format according to the file extension.
     */
    private function format(string $file): string
    {
        return (match (true) { // @phpstan-ignore-line
            str_ends_with($file, '.js') => fn () => "<script type=\"module\" src=\"/twauikit/script/{$file}\" defer></script>",
            str_ends_with($file, '.css') => fn () => "<link href=\"/twauikit/style/{$file}\" rel=\"stylesheet\" type=\"text/css\">",
        })();
    }

    /**
     * Load the manifest file and retrieve the desired data.
     */
    private function manifest(string $file, ?string $index = null): string|array
    {
        $content = json_decode(file_get_contents(__DIR__.'/../../../dist/.vite/manifest.json'), true);



        return data_get($content[$file], $index);
    }
}
