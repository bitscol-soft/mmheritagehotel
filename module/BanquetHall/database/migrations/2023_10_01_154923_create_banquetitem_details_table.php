<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquetitemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banquetitem_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('booking_id')->nullable()->constrained('banquet_bookings');
            $table->text('item_name')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('item_price')->nullable();
            $table->decimal('item_discount', 16, 2)->nullable();
            $table->decimal('discount_amount', 16, 2)->nullable();
            $table->decimal('service_charge', 16, 2)->nullable();
            $table->decimal('total_amount', 16, 2)->default(0);
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banquetitem_details');
    }
}
