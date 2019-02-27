<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        # 商用環境以外だった場合、SQLログを出力する
        if (config('app.env') !== 'production') {
            DB::listen(function ($query) {
                $sql = $query->sql;
                foreach ($query->bindings as $binding) {
                    if (is_string($binding)) {
                        $binding = "'{$binding}'";
                    } elseif ($binding === null) {
                        $binding = 'NULL';
                    } elseif ($binding instanceof Carbon) {
                        $binding = "'{$binding->toDateTimeString()}'";
                    }
                   $sql = preg_replace("/\?/", $binding, $sql, 1);
                }
                \Log::info("Query Time:{$query->time}ms] $sql");
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
