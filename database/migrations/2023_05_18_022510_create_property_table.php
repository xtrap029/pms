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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('entity_name');
            $table->unsignedBigInteger('property_category_id');
            $table->text('description');
            $table->string('serial_no');
            $table->string('property_no');
            $table->unsignedBigInteger('property_uom_id');
            $table->decimal('unit_value', 10, 2)->nullable();
            $table->decimal('qty_per_card', 10, 2)->nullable();
            $table->decimal('qty_per_count', 10, 2)->nullable();
            $table->unsignedBigInteger('property_location_id');
            $table->unsignedBigInteger('property_condition_id');
            $table->string('remarks')->nullable();
            $table->date('date_added');
            $table->boolean('status')->default(1);
            $table->boolean('is_available')->default(1);
            $table->boolean('is_disposed')->default(0);
            $table->unsignedBigInteger('person_accountable_id');
            $table->string('image');
            $table->integer('count_borrow')->default(0);
            $table->integer('count_purchase')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('property_category_id')->references('id')->on('property_categories');
            $table->foreign('property_uom_id')->references('id')->on('property_uoms');
            $table->foreign('property_location_id')->references('id')->on('property_locations');
            $table->foreign('property_condition_id')->references('id')->on('property_conditions');
            $table->foreign('person_accountable_id')->references('id')->on('user_references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};
