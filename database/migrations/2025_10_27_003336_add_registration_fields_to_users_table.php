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
            $table->string('mobile_number')->nullable()->after('email');
            $table->string('company')->nullable()->after('mobile_number');
            $table->string('position')->nullable()->after('company');
            $table->boolean('is_admin')->default(false)->after('position');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile_number', 'company', 'position', 'is_admin']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
