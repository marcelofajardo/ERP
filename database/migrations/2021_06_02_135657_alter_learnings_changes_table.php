<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLearningsChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('learnings', function (Blueprint $table) {
            $table->string('learning_user')->nullable();
            $table->string('learning_vendor')->nullable();
            $table->string('learning_subject')->nullable();
            $table->string('learning_module')->nullable();
            $table->string('learning_submodule')->nullable();
            $table->string('learning_assignment')->nullable();
            $table->string('learning_duedate')->nullable();
            $table->string('learning_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('learnings', function (Blueprint $table) {
            $table->dropField('learning_user');
            $table->dropField('learning_vendor');
            $table->dropField('learning_subject');
            $table->dropField('learning_module');
            $table->dropField('learning_submodule');
            $table->dropField('learning_assignment');
            $table->dropField('learning_duedate');
            $table->dropField('learning_status');
        });
    }
}
