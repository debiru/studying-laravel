<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_kana')->after('name');
            $table->date('birthday')->nullable()->after('name_kana');
            $table->enum('gender', ['UNSET', 'MALE', 'FEMALE'])->default('UNSET')->after('birthday');
            $table->string('address_zipcode', 16)->after('gender');
            $table->string('address')->after('address_zipcode');
            $table->string('phone_number', 32)->after('address');
            $table->enum('role', ['USER', 'OWNER'])->default('USER')->after('phone_number');
            $table->enum('status', ['REGISTERED', 'PROVISIONAL', 'BANNED', 'DELETED'])->default('PROVISIONAL')->after('role');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['name_kana', 'birthday', 'gender', 'address_zipcode', 'address', 'phone_number', 'role', 'status'];
            foreach ($columns as $column) {
                $table->dropColumn($column);
            }
            $table->dropSoftDeletes();
        });
    }
};
