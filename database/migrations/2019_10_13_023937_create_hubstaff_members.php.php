<?php   

use Illuminate\Support\Facades\Schema;  
use Illuminate\Database\Schema\Blueprint;   
use Illuminate\Database\Migrations\Migration;   

class CreateHubstaffMembers extends Migration   
{   
    /** 
     * Run the migrations.  
     *  
     * @return void 
     */ 
    public function up()    
    {   
         Schema::create('hubstaff_members', function (Blueprint $table) {   
            $table->increments('id');   
            $table->integer('hubstaff_user_id');    
            $table->integer('user_id'); 
            $table->float('pay_rate', 8, 2);    
            $table->float('bill_rate', 8, 2);   
            $table->string('currency')->nullable(); 
            $table->softDeletes();  
            $table->timestamps();   
        }); 
    }   

    /** 
     * Reverse the migrations.  
     *  
     * @return void 
     */ 
    public function down()  
    {   
        Schema::dropIfExists('hubstaff_members');   
    }   
}