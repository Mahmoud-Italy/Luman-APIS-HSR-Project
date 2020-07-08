<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('donation_option1');
            $table->text('donation_option2');
            $table->text('donation_option3');
            $table->text('donation_option4');
            
            $table->text('goal')->nullable();
            $table->decimal('goal_amount', 12,2)->nullable();
            
            $table->boolean('status')->default(true);
            $table->boolean('completed')->default(false);
            $table->boolean('auto_complete_on_goal')->default(false);
            $table->boolean('allow_custome_amounts')->default(false);
            $table->timestamps();
        });

        Schema::create('project_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned();
            $table->string('locale')->index();
            $table->string('slug');
            $table->string('title');
            $table->longText('body')->nullable();
            $table->timestamps();

            $table->unique(['slug', 'locale']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_translations');
    }
}
