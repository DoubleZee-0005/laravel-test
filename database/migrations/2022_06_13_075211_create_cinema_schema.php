<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * // ============================ Answer ===================================
     * // we can get movies from movies table and their times by the show_id
     * // ========================================================================
     * 
     * As a user I want to only see the shows which are not booked out
     * // ============================ Answer ==================================
     * // we can get movies with show and from show we will get show_room from show_room we will get show_room_seats where is booked_by is null if 
     * // there is any seat with booked_by null then that show is not booked.
     * // ========================================================================
     * 

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * // ============================ Answer ===================================
     * // Admin can create different shows and assign movies respectively
     * // ========================================================================
     * As a cinema owner I want to run multiple films at the same time in different showrooms
     * // ============================ Answer ===================================
     * // Admin can create different shows and assign show rooms respectively
     * // ========================================================================

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * // ============================ Answer ===================================
     * // show_prize will do the trick
     * // ========================================================================
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat
     * // ============================ Answer ===================================
     * // seat_type will work for this
     * // ========================================================================

     **Seating**
     * As a user I want to book a seat
     * // ============================ Answer =================================
     * // booked_by
     * // ======================================================================
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * // ============================ Answer =================================
     * // booked_by and seat type
     * // ======================================================================
     * As a user I want to see which seats are still available
     * // ============================ Answer =================================
     * // booked_by null
     * // ======================================================================
     * As a user I want to know where I'm sitting on my ticket
     * // ============================ Answer =================================
     * // booked_by is user_id
     * // ======================================================================
     * As a cinema owner I dont want to configure the seating for every show
      // ============================ Answer =================================
     * // for this we can write model observer to create seats etc for every show
     * // ======================================================================
     */
    public function up()
    {
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
        Schema::create('show_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('show_rooms_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId("show_room_id")->references("id")->on("show_rooms")->onDelete("cascade");
            $table->integer('type')->default(1)->comment("[
                1 => 'normal',
                2 => 'vip seat',
                3 => 'couple seat',
                4 => 'super vip,                
            ]");
            //like-wise if there is any other type
            $table->double('extra_charge')->nullable();
            $table->foreignId("booked_by")->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId("show_room_id")->references("id")->on("show_rooms")->onDelete("cascade");
            $table->double('show_price')->default(0);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('released_at');
            $table->timestamps();
        });

        Schema::create('movie_show', function (Blueprint $table) {
            $table->id();
            $table->foreignId("movie_id")->references("id")->on("movies")->onDelete("cascade");
            $table->foreignId("show_id")->references("id")->on("shows")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('show_rooms_seats');
        Schema::dropIfExists('show_rooms');
        Schema::dropIfExists('movie_show');
    }
}
