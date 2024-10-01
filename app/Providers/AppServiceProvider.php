<?php 
namespace App\Providers;

use App\Models\EventUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Wpint\Support\Facades\WPAPI;

use function Illuminate\Events\queueable;

class AppServiceProvider extends ServiceProvider
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

        // This piece of code demonstrates the idea of notifying those who's been attended to any of the event on the site
        // I know it could be better :/
        WPAPI::hook()
        ->name('save_post_event')
        ->callback(function(){
            global $post;
            if(!$post) return; 
            if($post->post_type !== 'event') return;
            $mails = EventUser::select('email')->get();
            $mails->each(function($m) use ($post){
                wp_mail(
                    $m->email,
                    'Event Update',
                    'Some Events has been created or updated ' . $post->post_title  . ' event',
                );
            });

        })
        ->register();
        
    }

}