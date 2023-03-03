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
        Schema::table('users', function (Blueprint $table) {
            //$table->enum('role',['Admin','Teacher','Student'])->default('Admin')->change();
           // $table->enum('role',['Admin','Teacher','Student'])->default('Admin');
            \DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM('Admin','Teacher','Student') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->enum('role',['user','admin'])->default('user')->change();
        });
    }
};
