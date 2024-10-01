<?php
/**
 * Plugin Name:     Event Plugin assignment (Ali Barzegar)
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     This is an wordpress event assignment plugin
 * Author:          Ali Barzegar Rahimi
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wpint-plugin
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         WPINT_Plugin
 */

use Database\Migrations\EventUserAttendances;
use Wpint\Support\Facades\Migration;

define('WPINT_PLUGIN_PATH', dirname(__FILE__));

define('WPINT_PLUGIN_URI', plugin_dir_url(__FILE__));

$app = require_once dirname(__FILE__) . "/bootstrap/app.php";

// Your code starts here.
function wpint_plugin_activation() 
{
    // create tables
    Migration::up([
        EventUserAttendances::class
    ]);
} 
register_activation_hook( __FILE__, 'wpint_plugin_activation' );


function wpint_plugin_deactivation() 
{
    // remove all created tables
    Migration::down([
        EventUserAttendances::class
    ]);

    // Clear the permalinks to remove our post type's rules from the database.
	flush_rewrite_rules();

}
register_deactivation_hook( __FILE__, 'wpint_plugin_deactivation' );
