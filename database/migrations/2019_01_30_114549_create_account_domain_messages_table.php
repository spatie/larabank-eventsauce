<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountDomainMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('account_domain_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_id', 36);
            $table->string('event_type', 100);
            $table->string('aggregate_root_id', 36)->nullable()->index();
            $table->dateTime('recorded_at', 6)->index();
            $table->text('payload');
        });
    }

    public function down()
    {
        Schema::dropIfExists('my_aggregate_root_domain_messages');
    }
}
