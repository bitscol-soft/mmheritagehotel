<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquetProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banquet_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('booking_id')->nullable()->constrained('banquet_bookings');
            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('product_price')->nullable();
            $table->decimal('product_discount', 16, 2)->nullable();
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
        Schema::dropIfExists('banquet_product_details');
    }
}
