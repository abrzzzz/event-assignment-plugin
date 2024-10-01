<?php 
namespace App\Providers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Wpint\Support\Facades\WPAPI;
use Wpint\WPAPI\Hook\Enum\HookTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Inertia\ResponseFactory;

class InertiaServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        // Register your service
        $this->app->singleton(ResponseFactory::class);
    }

    /**
     * Bootstrap any application service
     *
     * @return void
     */
    public function boot(): void
    {


        $this->app->extend(ResponseFactory::class, function ($factory, $app) {
            return new class extends ResponseFactory {
                public function render($component, $props = [])
                {
                    if ($props instanceof Arrayable) {
                        $props = $props->toArray();
                    }
                    
                    $headers = array_change_key_case(getallheaders(), CASE_LOWER);

                    $page = [
                        'component' => $component,
                        'props'     => array_merge($this->sharedProps, $props),
                        'version'   =>  $this->getVersion(),
                        'url'   =>  request()->getRequestUri()
                    ];
                    if(
                        isset($headers['x-requested-with'])
                        && $headers['x-requested-with'] === 'XMLHttpRequest'
                        && isset($headers['x-inertia'])
                        && $headers['x-inertia'] === 'true'
                    ){
                           
                        return wp_send_json($page);
                    }

                    echo view('app', ['page' => $page]);
                }
            };
        });

        $this->registerBladeDirective();
        $this->registerRequestMacro();

        /**
         * add a custom Hook (action or filter ) 
         * default type is ACTION 
         */
        WPAPI::hook()
        ->name('theme_page_templates')
        ->type(HookTypeEnum::FILTER)
        ->callback(function($templates)
        {
            $templates['inertia-app'] = 'App template';
            return $templates;
        })
        ->register();

        WPAPI::hook()
        ->name('init')
        ->type(HookTypeEnum::ACTION)
        ->callback(function()
        {
            header('Vary: Accept');
            header('X-Inertia: true');
            $plugin_template = dirname(plugin_dir_path(__FILE__), 2) . '/resources/views/app.blade.php';
            Inertia::setRootView($plugin_template);

                // Multiple values
                Inertia::share([
                    // Synchronously
                    'site' => [
                        'name'          =>  get_bloginfo('name'),
                        'description'   =>  get_bloginfo('description'),
                        'public_url'    =>  plugin_dir_url(dirname(__FILE__, 2)) . 'public'
                    ],
                    // Lazily
                    'auth' => function () {
                        if (is_user_logged_in()) {
                            return [
                                'user' => wp_get_current_user()
                            ];
                        }
                    }, 
                    'security'     => [
                        'nonce' =>  wp_create_nonce('assignment-security-nonce')
                    ],
                    'primary_menu' => function () {
                        // // Synchronously using array
                        if($primaryMenu = wp_get_nav_menu_items(config('labenter.primary_menu')))
                        {
                                return array_map(function ($menu_item) {
                                    return [
                                        'id'   => $menu_item->ID,
                                        'link' => $menu_item->url,
                                        'name' => $menu_item->title,
                                    ];
                                }, $primaryMenu);
                        }
                        return null;
                    }
                    
                ]);

                    // If you're using Laravel Mix, you can
                    // use the mix-manifest.json for this.
                    // $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

                    // Inertia::version($version);
        })
        ->register();



    }

    protected function registerBladeDirective()
    {
        Blade::directive('inertia', function () {
            return '<div id="app"  data-page="{{ json_encode($page) }}"></div>';
        });

        Blade::directive('inertiaHead', function () {
            return <<<'EOT'
                <?php if (isset($page) && isset($page['headManager']) && $page['headManager']->tags()): ?>
                    <?= $page['headManager']->tags(); ?>
                <?php endif; ?>
            EOT;
        });
    }

    protected function registerRequestMacro()
    {
        Request::macro('inertia', function () {
            return boolval($this->header('X-Inertia'));
        });
    }

    
}
