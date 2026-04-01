<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete()->after('loan_id');
            $table->string('fine_status')->default('tidak_ada')->after('fine'); // tidak_ada, belum_lunas, lunas
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['confirmed_by', 'fine_status']);
        });
    }
};
