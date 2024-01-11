<?php

namespace Hotmeteor\Titles;


use Illuminate\Support\ServiceProvider;

class TitlesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/titles.php', 'titles'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/titles.php' => config_path('titles.php'),
        ]);
    }
}
