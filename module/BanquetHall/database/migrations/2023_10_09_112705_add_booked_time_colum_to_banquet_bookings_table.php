<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookedTimeColumToBanquetBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banquet_bookings', function (Blueprint $table) {
            $table->string('booked_time')->nullable()->after('emergency_cont_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banquet_bookings', function (Blueprint $table) {
            //
        });
    }
}
