<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('pengajuan_status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('status');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete()->after('pengajuan_status');
            $table->text('rejection_reason')->nullable()->after('confirmed_by');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['pengajuan_status', 'confirmed_by', 'rejection_reason']);
        });
    }
};
