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
        Schema::create('quotation', function (Blueprint $table) {
            $table->id();
            // New field to accept "JPS/IP/P/PK/S.H/12026 (PPP)"
            $table->string('quotation_no'); 
            $table->text("file_name");
            $table->text("title");
            $table->text("specializations");
            $table->timestamp("begin_register_date")->nullable();
            $table->timestamp("end_register_date")->nullable();
            $table->timestamp("closing_date")->nullable();
            $table->text("slip_path");
            $table->text("site_visit_location");
            $table->timestamp("site_visit_date")->nullable();
            $table->text("advert_path");
            $table->text("serial_number");
            $table->text("organization");
            $table->text("status");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation');
    }
};
