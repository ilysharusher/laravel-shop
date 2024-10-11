<?php

use App\Models\{Brand, Category, Product};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('title');
            $table->string('thumbnail')->nullable();
            $table->unsignedInteger('price')->default(0);

            $table->foreignIdFor(Brand::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('category_product', static function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('category_product');
            Schema::dropIfExists('products');
        }
    }
};