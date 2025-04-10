<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->nullable();
            $table->date('date')->nullable();
            $table->string('password', 255);
            $table->string('email',100)->unique();
            $table->string('phone', 20)->unique()->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['admin', 'customer'])->nullable();

            // $table->rememberToken();
            // $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
            $table->decimal('price', 10,2);
            $table->unsignedInteger('stock');
            //khoa ngoai
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->decimal('total_price', 10,2);
            $table->string('image',255);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //khoa ngoai
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('total_price', 10,2);
            $table->enum('status', ['pending','processing','shipped','completed','cancelled']);
            $table->timestamp('create_at');
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            //khoa ngoai
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->decimal('price', 10,2);
        });

        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            //khoa ngoai
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedInteger('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};
