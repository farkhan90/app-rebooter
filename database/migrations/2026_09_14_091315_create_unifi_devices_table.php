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
        Schema::create('unifi_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('ip_address');
            $table->string('ssh_user');
            $table->text('ssh_password'); // Disimpan terenkripsi
            $table->string('reboot_time')->nullable(); // format HH:mm (contoh: 03:00)
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_reboot_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unifi_devices');
    }
};
