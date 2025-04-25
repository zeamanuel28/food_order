<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_restaurants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('address');
            $table->string('contact_number');
            $table->text('description')->nullable();
            $table->string('location');
            $table->unsignedBigInteger('restaurant_admin_id');
            $table->timestamps();

            // Foreign key constraint for the restaurant admin (User model)
            $table->foreign('restaurant_admin_id')
                  ->references('id')->on('users') // Assuming the 'users' table is used for admins
                  ->onDelete('cascade'); // Optional: deletes restaurants if the associated user is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
