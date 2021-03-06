<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('restrict');

            $table->unsignedInteger('designation_id');
            $table->foreign('designation_id')->references('id')->on('designations')->onUpdate('cascade')->onDelete('restrict');

            $table->string('employee_code', '10');
            $table->string('first_name', '50');
            $table->string('middle_name', '50')->nullable();
            $table->string('last_name', '50');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->dateTime('logged_on')->nullable();
            $table->rememberToken();

            $table->tinyInteger('status')->default('1')->comment('1-Active, 0-Inactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
