<?php

namespace App\Services;

use Exception;
use phpseclib4\Net\SSH2;

class SshService
{
    public function rebootDevice(string $ip, string $user, string $password): array
    {
        try {
            // Inisialisasi koneksi dengan timeout 5 detik
            $ssh = new SSH2($ip, 22, 5);

            if (! $ssh->login($user, $password)) {
                return [
                    'success' => false,
                    'message' => "Autentikasi SSH gagal untuk IP: {$ip}",
                ];
            }

            // Eksekusi perintah reboot pada UniFi OS / BusyBox
            $ssh->exec('/sbin/reboot');

            return [
                'success' => true,
                'message' => "Perintah reboot berhasil dikirim ke {$ip}",
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan koneksi: '.$e->getMessage(),
            ];
        }
    }
}
