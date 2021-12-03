<?php

namespace AceLords\Core\Providers;

use Illuminate\Support\ServiceProvider;
use AceLords\Core\Library\SiteConstants;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        \View::composer(
            [
                '*',
            ],
        
            function ($view) {
                $view->with((new SiteConstants())->data());
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @datetime([Auth::user()->created_at, 'd-m-Y H:i:s'])
         */
        $this->app['blade.compiler']->directive('datetime', function ($expression) {
            return "<?php echo e(localizeDateFormat(" . $expression . ")); ?>";
        });
    }
}
