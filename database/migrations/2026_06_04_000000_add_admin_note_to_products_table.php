<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'admin_note')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'admin_note')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
    }
};
