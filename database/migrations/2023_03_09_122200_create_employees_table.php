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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->string("email",50)->unique();
            $table->bigInteger("mobile_number")->unique();
            $table->string("department_name",30);
            $table->date("hiredate");
            $table->string('city');
            $table->enum("gender",['Male','Female','Other']);
            $table->integer("salary");
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
};
