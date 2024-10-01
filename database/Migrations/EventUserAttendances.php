<?php 
namespace Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Wpint\Contracts\Migration\MigrationContract;
use Illuminate\Support\Facades\Schema;

class EventUserAttendances implements MigrationContract
{

    /**
     * up should construct your database
     *
     * @return void
     */
    public static function up()
    {
        if(! Schema::hasTable('event_user_attendances'))
        {
            Schema::create('event_user_attendances', function (Blueprint $table) {

                $table->string('email');
                $table->foreignId('event_id')
                ->references('ID')
                ->on()
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
                $table->timestamps();
                
            });
        }
    }

    /**
     * down should deconstruct your database
     *
     * @return void
     */
    public static function down()
    {
        Schema::dropIfExists('example');
    }

}