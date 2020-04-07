<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->string('name')->unique();
            $table->string('password');
            $table->timestamps();
        });

        App\Models\Admin::create([
            'name'     => getenv('ADMIN_NAME'),
            'password' => Illuminate\Support\Facades\Hash::make(getenv('ADMIN_PASS')),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
