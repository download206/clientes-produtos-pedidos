<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();

            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Exemplo de chave estrangeira com ON DELETE CASCADE

        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
