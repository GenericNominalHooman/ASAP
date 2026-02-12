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
            $table->timestamps();
        });

        Schema::table('quotation', function (Blueprint $table) {
            $table->text("file_name");
            $table->text("title");
            $table->text("specializations");
            $table->timestamp("begin_register_date");
            $table->timestamp("end_register_date");
            $table->timestamp("closing_date");
            $table->text("slip_path");
            $table->text("site_visit_location");
            $table->timestamp("site_visit_date");
            $table->text("advert_path");
            $table->text("serial_number");
            $table->text("organization");
            $table->text("status");
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
