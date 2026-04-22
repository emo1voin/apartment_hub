<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
            $table->string('phone', 20)->nullable()->after('password');
            $table->string('avatar')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('country')->nullable()->after('bio');
            $table->string('city')->nullable()->after('country');
            $table->string('address')->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('postal_code');
            $table->boolean('is_active')->default(true)->after('birth_date');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'avatar', 'bio', 'country', 'city',
                'address', 'postal_code', 'birth_date', 'is_active',
                'last_login_at', 'last_login_ip'
            ]);
        });
    }
};
