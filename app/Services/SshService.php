<?php

namespace App\Services;

use phpseclib4\Net\SSH2;
use Exception;

class SshService
{
    public function rebootDevice(string $ip, string $user, string $password): array
    {
        try {
            // Timeout 10 detik untuk memastikan handshake selesai
            $ssh = new SSH2($ip, 22, 10);

            // Coba login ke perangkat UniFi
            if (!$ssh->login($user, $password)) {
                return [
                    'success' => false,
                    'message' => "Gagal login SSH ke {$ip}. Periksa kembali Username dan Password Device SSH Authentication di UniFi Controller."
                ];
            }

            // Jalankan perintah reboot pada perangkat UniFi
            // Perangkat UniFi umumnya menggunakan perintah reboot langsung atau via busybox
            $ssh->exec('/sbin/reboot');

            return [
                'success' => true,
                'message' => "Sinyal reboot berhasil dikirim ke perangkat {$ip}."
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Gagal terhubung ke {$ip}: " . $e->getMessage()
            ];
        }
    }
}
