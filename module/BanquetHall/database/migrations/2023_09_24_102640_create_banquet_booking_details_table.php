<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquetBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banquet_booking_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('booking_id')->nullable()->constrained('banquet_bookings');
            $table->integer('category_id')->nullable();
            $table->integer('halls_id')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->integer('guest_count')->nullable();
            $table->integer('infant_count')->nullable();
            $table->decimal('room_discount', 16, 2)->nullable();
            $table->decimal('discount_amount', 16, 2)->nullable();
            $table->decimal('service_charge', 16, 2)->nullable();
            $table->decimal('total_amount', 16, 2)->default(0);
            $table->integer('status')->nullable();
            $table->integer('discount_type')->nullable();
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
        Schema::dropIfExists('banquet_booking_details');
    }
}
