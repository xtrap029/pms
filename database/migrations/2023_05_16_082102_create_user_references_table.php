<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_references', function (Blueprint $table) {
            $table->id();
            $table->string('employee_no');
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('is_admin')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_position_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_position_id')->references('id')->on('user_positions');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_references');
    }
};
