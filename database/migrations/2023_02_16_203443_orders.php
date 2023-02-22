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
        Schema::create(
            'orders', static function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('status', '128');
                $table->integer('ecommerce_id');
                $table->integer('partner_id');
                $table->date('delivery_date');
                $table->string('shipping_address', 2048);
                $table->string('customer_name');
                $table->timestamp('created_at');
                $table->timestamp('updated_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
