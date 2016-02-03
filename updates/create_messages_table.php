<?php namespace KurtJensen\AuthNotice\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateMessagesTable extends Migration
{

    public function up()
    {
        Schema::create('kurtjensen_authnotice_messages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('mess_id')->unsigned()->nullable()->index();
            $table->string('plugin')->nullable();
            $table->string('author')->nullable();
            $table->string('lang')->nullable();
            $table->enum('level', array('info', 'warning', 'danger'));
            $table->text('text')->nullable();
            $table->boolean('send')->default(false);
            $table->boolean('read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->text('source')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kurtjensen_authnotice_messages');
    }

}
