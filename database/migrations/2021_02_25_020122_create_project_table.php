<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('process_number', 20)->unique();
            $table->string('agreement_number', 20)->unique();
            $table->enum('action', ['MSD','MH','SAA','SES','RES','DRE']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('city', 50);
            $table->text('resume')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project');
    }
}
