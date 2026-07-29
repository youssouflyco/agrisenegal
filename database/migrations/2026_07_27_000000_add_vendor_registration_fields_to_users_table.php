<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cni_front_photo')->nullable()->after('cni');
            $table->string('cni_back_photo')->nullable()->after('cni_front_photo');
            $table->string('farm_name')->nullable()->after('cni_back_photo');
            $table->string('production_type')->nullable()->after('farm_name');
            $table->string('farm_photo')->nullable()->after('production_type');
            $table->string('business_name')->nullable()->after('farm_photo');
            $table->string('business_type')->nullable()->after('business_name');
            $table->string('business_photo')->nullable()->after('business_type');
        });

        if (DB::getDriverName() === 'mysql' && Schema::hasColumn('users', 'status')) {
            DB::statement("ALTER TABLE users MODIFY status ENUM('en_attente','actif','bloqué','archivé') NOT NULL DEFAULT 'actif'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasColumn('users', 'status')) {
            DB::statement("ALTER TABLE users MODIFY status ENUM('actif','bloqué','archivé') NOT NULL DEFAULT 'actif'");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cni_front_photo',
                'cni_back_photo',
                'farm_name',
                'production_type',
                'farm_photo',
                'business_name',
                'business_type',
                'business_photo',
            ]);
        });
    }
};
