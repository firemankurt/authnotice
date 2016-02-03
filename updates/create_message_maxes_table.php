<?php namespace KurtJensen\AuthNotice\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateMessageMaxesTable extends Migration
{

    public function up()
    {
        Schema::create('kurtjensen_authnotice_message_maxes', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('row_id')->unsigned();
            $table->integer('max_id')->unsigned();
            $table->string('plugin');
            $table->primary('plugin');

        });
    }

    public function down()
    {
        Schema::dropIfExists('kurtjensen_authnotice_message_maxes');
    }

}
