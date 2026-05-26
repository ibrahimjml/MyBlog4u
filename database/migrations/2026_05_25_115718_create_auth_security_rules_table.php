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
      Schema::create('auth_security_rules', function (Blueprint $table) {
            $table->id();
            $table->boolean('require_email_verification')->nullable()->default(true);
            $table->boolean('require_captcha')->nullable()->default(false);
            $table->string('recaptcha_sitekey')->nullable();
            $table->string('recaptcha_secretkey')->nullable();
            $table->boolean('allow_registration')->nullable()->default(true);
            $table->string('allowed_email_domains')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_security_rules');
    }
};
