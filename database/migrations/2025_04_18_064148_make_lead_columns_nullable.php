<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead', function (Blueprint $table) {
            // Make columns nullable
            $table->string('business_name', 80)->nullable()->change();
            $table->date('dob')->nullable()->change();
            $table->string('vat', 20)->nullable()->change();
            $table->string('phone', 15)->nullable()->change();
            $table->string('phone2', 15)->nullable()->change();
            $table->string('mobile', 15)->nullable()->change();
            $table->string('email', 254)->nullable()->change();
            $table->string('email2', 254)->nullable()->change();
            $table->string('website', 255)->nullable()->change();
            $table->string('linkedin', 255)->nullable()->change();
            $table->string('facebook', 255)->nullable()->change();
            $table->string('instagram', 255)->nullable()->change();
            $table->string('twitter', 255)->nullable()->change();
            $table->string('youtube', 255)->nullable()->change();
            $table->string('tiktok', 255)->nullable()->change();
            $table->text('notes')->nullable()->change();
            $table->string('country_id', 2)->nullable()->change();
            $table->string('province', 80)->nullable()->change();
            $table->string('city', 50)->nullable()->change();
            $table->string('locality', 80)->nullable()->change();
            $table->string('street', 80)->nullable()->change();
            $table->string('zipcode', 10)->nullable()->change();
            $table->date('schedule_contact')->nullable()->change();
            $table->unsignedBigInteger('industry_id')->nullable()->change();
            $table->decimal('latitude', 10, 8)->nullable()->change();
            $table->decimal('longitude', 11, 8)->nullable()->change();
            $table->boolean('opt_in')->nullable()->change();
            $table->text('tags')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revert columns to their original state
            // Note: You may need to adjust this based on your original schema
            $table->string('business_name', 80)->nullable(false)->change();
            $table->date('dob')->nullable(false)->change();
            $table->string('vat', 20)->nullable(false)->change();
            $table->string('phone', 15)->nullable(false)->change();
            $table->string('phone2', 15)->nullable(false)->change();
            $table->string('mobile', 15)->nullable(false)->change();
            $table->string('email', 254)->nullable(false)->change();
            $table->string('email2', 254)->nullable(false)->change();
            $table->string('website', 255)->nullable(false)->change();
            $table->string('linkedin', 255)->nullable(false)->change();
            $table->string('facebook', 255)->nullable(false)->change();
            $table->string('instagram', 255)->nullable(false)->change();
            $table->string('twitter', 255)->nullable(false)->change();
            $table->string('youtube', 255)->nullable(false)->change();
            $table->string('tiktok', 255)->nullable(false)->change();
            $table->text('notes')->nullable(false)->change();
            $table->string('country_id', 2)->nullable(false)->change();
            $table->string('province', 80)->nullable(false)->change();
            $table->string('city', 50)->nullable(false)->change();
            $table->string('locality', 80)->nullable(false)->change();
            $table->string('street', 80)->nullable(false)->change();
            $table->string('zipcode', 10)->nullable(false)->change();
            $table->date('schedule_contact')->nullable(false)->change();
            $table->unsignedBigInteger('industry_id')->nullable(false)->change();
            $table->decimal('latitude', 10, 8)->nullable(false)->change();
            $table->decimal('longitude', 11, 8)->nullable(false)->change();
            $table->boolean('opt_in')->nullable(false)->change();
            $table->text('tags')->nullable(false)->change();
        });
    }
};