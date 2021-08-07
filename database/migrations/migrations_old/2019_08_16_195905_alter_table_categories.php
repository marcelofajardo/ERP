<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function($table)
        {
            $table->string('brand_segment', 2)->after('magento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function($table)
        {
            $table->dropColumn('brand_segment');
        });
    }
}


//UPDATE brands SET brand_segment='A' WHERE name IN ('Michael Kors','Tory Burch','Coach');
//
//UPDATE brands SET brand_segment='B' WHERE name IN ('Kenzo','Off-white','Marc Jacob','Love Moschino','Moschino','Moschino Culture');
//
//UPDATE brands SET brand_segment='C' WHERE name IN ('Alexander Mcqueen','Bottega Veneta','Burberry','Jimmy Choo','Issey Miyake','Red Valentino','Salvetore Farragamo','Stella Mccartney','Gucci','Balanciaga','Christian Louboutin','Chloe','Dior Homme','Dolce Babbana','Fendi','Givenchy','Miu Miu','Prada','Tods','Valentino','Versace','YSL','Bottega','Bvlgari','Celine','christian Dior','Tom Ford')