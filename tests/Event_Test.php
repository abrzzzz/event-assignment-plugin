<?php

use App\Models\EventUser;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use WP_Mock\Functions;

class Event_Test extends TestCase
{
	
	/** @test */
	public function user_can_attend_event(){

		   $event = Post::factory()->create();
		   $data = [
			   'email'		=>	'example@mail.com',
			   'security'		=>  wp_create_nonce('assignment-security-nonce')	
		   ];

		   $res = wp_remote_post("http://assignment.local:10013/events/{$event->ID}/attend", [
			'body'	=> $data
		   ]);
		   
		   
		   $this->assertTrue(
				EventUser::where('email', 'example@mail.com')->where('event_id', $event->ID)->count() > 0
			);
   }

}