<?php 
namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use WP_Error;

use function Illuminate\Events\queueable;

class Post extends Model
{
    use HasFactory;

    /**
     * $table
     *
     * @var string
     */
    protected $table = "posts";

    /**
     * $timestamps
     *
     * @var boolean
     */
    public $timestamps = false; 

    /**
     * $primaryKey
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * $fillable
     *
     * @var array
     */
    public $fillable = [
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];

    protected $appends = [
        'featured_image',
        'event_detail'
    ];

    protected function eventDetail(): Attribute
    {
        return Attribute::make(
            get: fn () => get_post_meta($this->ID, 'event_meta'),
        );
    }


    protected function featuredImage(): Attribute
    {
        return Attribute::make(
            get: fn () => get_the_post_thumbnail_url($this->ID),
        );
    }

    /**
     * This post has many meta
     *
     * @return void
     */
    public function meta()
    {
        return $this->hasMany(PostMeta::class, 'post_id');
    }

    /**
     * This post belongs to a user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    /**
     * This post has many comments
     *
     * @return void
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'comment_post_ID');
    }

    /**
     * This post has many taxonomies
     *
     * @return void
     */
    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class, 'term_relationships', 'object_id', 'term_taxonomy_id');
    }

    public function getPostMeta($key)
    {
        return get_post_meta($this->id, $key);
    }

}