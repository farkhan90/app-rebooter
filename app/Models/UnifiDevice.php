<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UnifiDevice extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'ip_address',
        'ssh_user',
        'ssh_password',
        'reboot_time',
        'is_active',
        'last_reboot_at',
    ];

    // Otomatis mengenkripsi kredensial SSH di DB
    protected $casts = [
        'ssh_password' => 'encrypted',
        'is_active' => 'boolean',
        'last_reboot_at' => 'datetime',
    ];
}
