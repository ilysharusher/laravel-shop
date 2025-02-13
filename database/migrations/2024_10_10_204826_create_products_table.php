<?php

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Product\Models\{Product};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', static function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->json('json_properties')->nullable();

            $table->string('title')->fulltext();
            $table->text('description')->fulltext()->nullable();

            $table->string('thumbnail')->nullable();
            $table->unsignedInteger('price')->default(0);

            $table->boolean('on_home_page')->default(false);
            $table->integer('sorting')->default(500);

            $table->foreignIdFor(Brand::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('category_product', static function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
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
