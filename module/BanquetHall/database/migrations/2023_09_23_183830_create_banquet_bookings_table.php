<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquetBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banquet_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('booking_number')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('purpose')->nullable();
            $table->unsignedBigInteger('purpose_id')->nullable();
            $table->string('booking_type')->nullable();
            $table->string('booking_from')->nullable();
            $table->string('emergency_cont_name')->nullable();
            $table->string('emergency_cont_phone')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->date('booking_date')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->text('check_in_note')->nullable();
            $table->decimal('sub_total', 16, 2)->default(0);
            $table->decimal('advanced_payment', 16, 2)->default(0);
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('payment_way')->nullable();
            $table->integer('vat_id')->nullable();
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('service_amount', 10, 2)->nullable();
            $table->integer('pay_by')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();



            $table->timestamps();
            // $table->foreign('purpose_id')->references('id')->on('hotel_booking_purpose');
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
        Schema::dropIfExists('banquet_bookings');
    }
}
