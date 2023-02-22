<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("full_name");
            $table->unsignedBigInteger("position_id");
            $table->foreign("position_id")->references("id")->on("positions");
            $table->date("entry_date");
            $table->string("telephone_number");
            $table->string("email");
            $table->float("salary");
            $table->string("photo");
            $table->unsignedBigInteger("admin_created_id");
            $table->unsignedBigInteger("admin_updated_id");
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
        Schema::dropIfExists('employees');
    }
}
