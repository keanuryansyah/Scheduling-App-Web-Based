<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_types', function (Blueprint $table) {
            // Tambah kolom warna (default abu-abu jika tidak diisi)
            $table->string('badge_color')->default('#6c757d')->after('job_type_name');
        });
    }

    public function down(): void
    {
        Schema::table('job_types', function (Blueprint $table) {
            $table->dropColumn('badge_color');
        });
    }
};