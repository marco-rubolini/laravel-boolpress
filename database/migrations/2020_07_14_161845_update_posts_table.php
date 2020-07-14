<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // aggiungiamo una colonna
            $table->unsignedBigInteger('category_id')->nullable()->after('slug');
            // Quale colonna sarà la mia foreign, in questo caso category_id
            $table->foreign('category_id')
            // che fa riferimento alla colonna id
            ->references('id')
            // della tabella categories
            ->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // La foreign si chiama nome della tabella_nome della colonna_foreign
            $table->dropForeign('posts_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
}
