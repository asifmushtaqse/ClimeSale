<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuantityColumnTypeInSaleManStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_man_stocks', function (Blueprint $table) {
            //
            $table->string('quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_man_stocks', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
    }
}
