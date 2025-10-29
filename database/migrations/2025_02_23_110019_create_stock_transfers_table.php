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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('account_id')->constrained('accounts', 'id');
            $table->foreignId('warehouse_from_id')->constrained('warehouses', 'id');
            $table->foreignId('warehouse_to_id')->constrained('warehouses', 'id');
            $table->foreignId('product_id')->constrained('products', 'id');
            $table->float('qty')->default(0);
            $table->float('expense')->default(0);
            $table->foreignId('transporter_id')->constrained('accounts', 'id');
            $table->string('status')->default('Unpaid');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->bigInteger('refID');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
