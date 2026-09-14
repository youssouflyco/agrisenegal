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
            // remove redundant columns from users table
            $table->dropColumn(['cni_front_photo', 'cni_back_photo', 'farm_name', 'production_type', 'farm_photo', 'business_name', 'business_type', 'business_photo', 'two_factor_secret', 'two_factor_confirmed_at', 'two_factor_enabled', 'archived_at', 'cni', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // add back the removed columns
            $table->string('cni')->nullable()->after('cni');
            $table->string('name')->nullable()->after('name');
            $table->string('cni_front_photo')->nullable()->after('cni_front_photo');
            $table->string('cni_back_photo')->nullable()->after('cni_back_photo');
            $table->string('farm_name')->nullable()->after('farm_name');
            $table->string('production_type')->nullable()->after('production_type');
            $table->string('farm_photo')->nullable()->after('farm_photo');
            $table->string('business_name')->nullable()->after('business_name');
            $table->string('business_type')->nullable()->after('business_type');
            $table->string('business_photo')->nullable()->after('business_photo');
            $table->text('two_factor_secret')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_confirmed_at');
            $table->boolean('two_factor_enabled')->default(false)->after('two_factor_enabled');
            $table->timestamp('archived_at')->nullable()->after('archived_at');
        });
    }
};
