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
            $table->index('tenant_id');
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->index('tenant_id');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('tenant_id');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->index('tenant_id');
        });
    }
};
