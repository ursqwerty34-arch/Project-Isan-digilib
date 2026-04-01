<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite tidak support ALTER COLUMN enum, gunakan raw SQL
        // Ubah due_date jadi nullable dan status enum tambah 'pending'
        Schema::table('loans', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
        });

        // Update enum status via raw (SQLite tidak support enum, tapi Laravel pakai string)
        // Cukup ubah default saja, karena SQLite simpan sebagai string
        DB::statement("UPDATE loans SET status = 'dipinjam' WHERE status NOT IN ('dipinjam', 'dikembalikan', 'pending')");
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->date('due_date')->nullable(false)->change();
        });
    }
};
