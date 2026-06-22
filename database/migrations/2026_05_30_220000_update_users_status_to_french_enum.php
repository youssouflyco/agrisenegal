<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['actif', 'bloqué', 'archivé'])->default('actif');
            });
        }

        if (! Schema::hasColumn('users', 'archived_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('archived_at')->nullable();
            });
        }

        DB::table('users')->where('status', 'active')->update(['status' => 'actif']);
        DB::table('users')->where('status', 'suspended')->update(['status' => 'bloqué']);
        DB::table('users')->where('status', 'archived')->update(['status' => 'archivé']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY status ENUM('actif','bloqué','archivé') NOT NULL DEFAULT 'actif'");
        }

        DB::table('users')
            ->where('status', 'archivé')
            ->whereNull('archived_at')
            ->update(['archived_at' => now()]);
    }

    public function down(): void
    {
        DB::table('users')->where('status', 'actif')->update(['status' => 'active']);
        DB::table('users')->where('status', 'bloqué')->update(['status' => 'suspended']);
        DB::table('users')->where('status', 'archivé')->update(['status' => 'archived']);

        if (DB::getDriverName() === 'mysql' && Schema::hasColumn('users', 'status')) {
            DB::statement("ALTER TABLE users MODIFY status VARCHAR(255) NOT NULL DEFAULT 'active'");
        }
    }
};
