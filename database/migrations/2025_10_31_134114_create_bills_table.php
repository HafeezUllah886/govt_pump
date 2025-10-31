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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('accounts', 'id');
            $table->foreignId('vehicle_id')->constrained('vehicles', 'id');
            $table->string('month');
            $table->date('start_date');
            $table->date('end_date');
            $table->float('total')->default(0);
            $table->date('bill_date')->nullable();
            $table->bigInteger('bill_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
