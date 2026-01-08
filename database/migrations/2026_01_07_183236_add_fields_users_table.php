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
        //
        Schema::table('users', function(Blueprint $table ){
            $table->string('phone_number')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function(Blueprint $table){
            $table->dropColumn('phone_number');
            $table->dropColumn('last_login_at');
            $table->dropColumn('profile_completed');
            $table->dropColumn('status');
        });

    }
};
