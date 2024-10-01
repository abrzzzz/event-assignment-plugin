<?php
namespace App\Controllers;

use App\Models\EventUser;
use WPINT\Framework\Include\Controller\Controller;
use App\Models\Post;
use Exception;
use Inertia\Inertia;
use Throwable;

use function Illuminate\Events\queueable;

class EventController extends Controller
{

    /**
     * Index page
     *
     * @return void
     */
    public function index()
    {

        $events = Post::where('post_type', 'event')
        ->where('post_status', 'publish')
        ->get();

        $eventTypes = get_terms([
            'taxonomy'      =>  'event_type',
            'hide_empty'    =>  false
        ]); 
    
        return Inertia::render("Events/Index", [
            'events'    =>  $events,
            'types'     =>  $eventTypes
        ]);
    }

    
    /**
     * Rest Events search
     *
     * @return void
     */
    public function search()
    {
        if(request()->has('key'))
        {
        
            $key = '%'.esc_attr(request()->get('key')).'%';
            $events = Post::where('post_type', 'event')
            ->where('post_status', 'publish')
            ->where('post_title', "LIKE", $key )
            ->get();
        
        }else
        {
            $events = Post::where('post_type', 'event')
            ->where('post_status', 'publish')
            ->get();
        }
        
        return  rest_ensure_response($events);
    }

    /**
     * Show page
     *
     * @param [type] $data
     * @return void
     */
    public function show($data)
    {
        try{

            $event = isset($data['event']) ? Post::find($data['event']) : null;

            if(!$event || $event->post_type !== 'event') return wp_redirect("/events");

            return Inertia::render("Events/Show", [
                'event' => $event
            ]);
            
        }catch(Throwable $th)
        {
            return Inertia::render("Event/Index");
        }

    }

    /**
     * Attend 
     *
     * @return void
     */
    public function attend($data)
    {   

        try{
            
            $event = isset($data['event']) ? Post::find(sanitize_text_field($data['event'])) : null;

            if (! $event || ! wp_verify_nonce( sanitize_text_field( wp_unslash( request()->get('security') ) ), 'assignment-security-nonce' ) ) {
                throw new Exception('Security Issues has been cought!');
            }

            if( ! request()->has('email') ||  sanitize_text_field( wp_unslash(request()->get('email'))) == "" )
                throw new Exception('Email has not been provided!');
        
            $email =  sanitize_text_field( wp_unslash(request()->get('email')));


            $eventUser = EventUser::updateOrCreate([
                'email'         =>  $email,
                'event_id'      =>  $event->ID
            ], [
            ]);

            queueable(function() use ($event, $email){
                wp_mail(
                    $email,
                    'Assignment Event',
                    'You Attended to the ' . $event->post_title  . ' event',
                );
            });

            // send email to the user
            return wp_send_json(
                $eventUser,
                200
            );

        } catch(Throwable $th) {

            return wp_send_json(
                array(
                  'errors' => [
                    $th->getMessage()
                  ],
                ),
                400
              );
        }

    }

}