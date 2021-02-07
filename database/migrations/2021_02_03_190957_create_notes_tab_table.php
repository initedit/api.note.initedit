<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_tab', function (Blueprint $table) {
            $table->integer('id',false);
            $table->string('slug');
            $table->string('title',2000);
            $table->mediumText('content');
            $table->integer('visibility');
            $table->integer('order_index');
            $table->integer('createdon');
            $table->integer('modifiedon')->nullable();
            $table->integer('status');
            $table->string('parent_id',100)->nullable();
            $table->index('slug');
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes_tab');
    }
}
