<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->boolean("is_owner")->default(false);
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('shows', function($table) {
            $table->increments('id');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->string('name');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('seats', function($table) {
            $table->increments('id');
            $table->string('seat_no');
            $table->double('amount', 8, 2);
            $table->enum('type', ['normal',  "gold", 'vip']);
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('bookings', function($table) {
            $table->increments('id');
            $table->integer('seat_id')->unsigned();
            $table->integer('show_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('seat_id')->references('id')->on('seats');
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('transactions', function($table) {
            $table->increments('id');
            $table->strings('seats_id');
            $table->string('order_id')->unique();
            $table->string('payment_id')->unique()->nullable();
            $table->integer('show_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->double('amount', 8, 2);
            $table->json('payment_data');
            $table->json('transaction_data')->nullable();
            $table->enum('status', ['active',  "success", 'failed']);
            $table->foreign('show_id')->references('id')->on('shows');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
