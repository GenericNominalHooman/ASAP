<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_applications', function (Blueprint $table) {
            $table->id();
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
            $table->text("owner");
            $table->text("status");
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_applications');
    }
};
