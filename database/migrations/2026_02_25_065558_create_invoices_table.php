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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('invoice_number',100)->unique();
            $table->decimal('subtotal',12,2)->default(0);
            $table->decimal('tax_amount',12,2)->default(0);
            $table->decimal('total',12,2)->default(0);
            $table->enum('status',['pending','paid','partially_paid','overdue','cancelled'])->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
