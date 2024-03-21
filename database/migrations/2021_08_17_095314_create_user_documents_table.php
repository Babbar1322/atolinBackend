<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('document_id');
            $table->string('url');
            $table->string('unique_id')->nullable();
            $table->enum('status', ['PENDING', 'APPROVED' , 'DISAPPROVED'])->default('PENDING');
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
        Schema::dropIfExists('user_documents');
    }
}
