<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_history_id')->constrained("upload_histories", 'id')->onDelete('cascade');
            $table->date('RptDt');
            $table->string('TckrSymb');
            $table->string('MktNm');
            $table->string('SctyCtgyNm');
            $table->string('ISIN');
            $table->string('CrpnNm');

            // Index para otimização das buscas
            $table->index(['RptDt', 'TckrSymb']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
