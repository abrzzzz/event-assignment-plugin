<?php 
namespace Database\Factories;

use App\Models\Example;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_title'    =>  fake()->sentence(),
            'post_type'     => 'event',
            'post_content'  =>  fake()->sentences(10),
            'post_excerpt'  =>  fake()->sentence(),
            'post_author'   => 1,
            'post_date'     => fake()->dateTime(),
            'post_date_gmt' => fake()->dateTime(),
            'post_status'   => 'publish',
            'comment_status'=>  'open',
            'ping_status'   => '',
            'post_password' =>  '',
            'to_ping'       => '',
            'pinged'        =>  '',
            'post_modified' => fake()->dateTime(), 
            'post_modified_gmt' => fake()->dateTime(),
            'post_content_filtered' => '',
            'post_parent' => 0,
            'guid'      =>  "",
            'menu_order' => 0,
            'post_mime_type'    =>  '',
            'comment_count' =>  0,
        ];
    }
}