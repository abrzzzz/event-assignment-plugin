<?php 
namespace App\Providers;

use App\Controllers\EventController;
use App\Models\Post;
use Wpint\WPAPI\Enqueuer\Enum\EnqueuerScopeEnum;
use Wpint\WPAPI\Hook\Enum\HookTypeEnum;
use Wpint\WPAPI\Metabox\Enum\MetaboxContextEnum;
use Wpint\WPAPI\Metabox\Enum\MetaboxPriorityEnum;
use Wpint\WPAPI\Setting\Enum\OptionGroupEnum;
use Illuminate\Support\ServiceProvider;
use Wpint\Support\CallbackResolver;
use Wpint\Support\Facades\WPAPI;

class WPServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        // Register your service
    }

    /**
     * Bootstrap any application service
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * e.g: add a custom shortcode
         */
        WPAPI::shortcode()
        ->tag('event_list')
        ->callback(function($atts = []){
            
            $events = Post::where('post_type', 'event')
            ->where('post_status', 'publish')
            ->get();

            return view('Client/Shortcode/event', [
                'events'  => $events
            ]); 

        })->register();
        
        /**
         * e.g: add a custom post type
         */
        WPAPI::postType()
        ->id('event')
        ->name('events')
        ->singularName('event')
        ->isPublic()
        ->hasArchive()
        ->register();
        add_post_type_support( 'event', 'thumbnail' );

        /**
         *  custom taxonomy
         */
        WPAPI::taxonomy()
        ->name('event_type')
        ->posttype('event')
        ->register();
        
        /**
         * add a custom cron interval
         */
        // WPAPI::cron()
        // ->addCronInterval('five_sec', 5, 'DO THIS EVERY 5 SEC');

        /**
         * add a custom cron job
         */
        // WPAPI::cron()
        // ->name('wpint_cron')
        // ->start(time())
        // ->every('five_sec')
        // ->execute(function(){
        //     $times = get_option('wpint_cron', 0);
        //     update_option('wpint_cron', $times + 1);
        // })
        // ->register();
            
        /**
         * add a custom metabox
         * default context is ADVANCES | default priority is DEFAULT
         */
        WPAPI::metabox()
        ->id('event_metabox')
        ->title('Event Meta')
        ->priority(MetaboxPriorityEnum::DEFAULT)
        ->context(MetaboxContextEnum::ADVANCES)
        ->callback(function($post, $args)
        {
            $event_meta = get_post_meta( $post->ID, 'event_meta', true );
            echo view('Admin/Metabox/event', [
                'event_meta'  => $event_meta
            ]);   
        })
        ->screen('event')
        ->metaKey('event_meta')
        ->postKey('event_meta')
        ->register();

        /**
         * add a custom Hook (action or filter ) 
         * default type is ACTION 
         * admin event list column 
         */
        WPAPI::hook()
        ->name('manage_event_posts_columns')
        ->type(HookTypeEnum::FILTER)
        ->callback(function($columns)
        {
            return array_merge($columns, ['event_date' => __('Event Date') , 'location' =>  __('Location')]);
    
        })
        ->register();
        
        WPAPI::hook()
        ->name('manage_event_posts_custom_column')
        ->type(HookTypeEnum::ACTION)
        ->callback(function($column_key)
        {
            global $post;
            $event_meta = get_post_meta($post->ID, 'event_meta', true);
            if ($column_key == 'event_date') {
                echo isset($event_meta['date']) ? $event_meta['date'] : '-';
            }    
            if ($column_key == 'location') {
                echo isset($event_meta['location']) ? $event_meta['location'] : '-';
            }    
        })
        ->register();


        WPAPI::hook()
        ->name('manage_edit-event_sortable_columns')
        ->type(HookTypeEnum::FILTER)
        ->callback(function($columns)
        {
            $columns['event_date'] = 'Event Date';
            return $columns;
        })
        ->register();

        WPAPI::hook()
        ->name('pre_get_posts')
        ->type(HookTypeEnum::ACTION)
        ->callback(function($query)
        {
            if (!is_admin()) {
                return;
            }
            
            $orderby = $query->get('orderby');
            if ($orderby == 'event_date') {
                $query->set('meta_key', 'event_date');
                $query->set('orderby', 'meta_value_num');
            }
        })
        ->register();

        // custom rest hook for searching events
        WPAPI::hook()
        ->name('rest_api_init')
        ->type(HookTypeEnum::ACTION)
        ->callback(function()
        {
            register_rest_route( 'wp/v2/', '/events', array(
                'methods' => 'GET',
                'callback' => function($data){
                    $controller = app(EventController::class);
                    return $controller->callAction('search', $data->get_params());
                },
              ));
        })
        ->register();

    }

}