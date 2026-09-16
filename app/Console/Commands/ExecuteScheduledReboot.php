<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExecuteScheduledReboot extends Command
{
    protected $signature = 'unifi:auto-reboot';

    protected $description = 'Menjalankan eksekusi reboot perangkat berdasarkan jadwal';

    public function handle(SshService $ssh)
    {
        $currentTime = now()->format('H:i');

        $devices = UnifiDevice::where('is_active', true)
            ->where('reboot_time', $currentTime)
            ->get();

        foreach ($devices as $device) {
            $this->info("Menjalankan reboot otomatis: {$device->name} ({$device->ip_address})");
            $res = $ssh->rebootDevice($device->ip_address, $device->ssh_user, $device->ssh_password);

            if ($res['success']) {
                $device->update(['last_reboot_at' => now()]);
                $this->info('Berhasil direboot.');
            } else {
                $this->error('Gagal: '.$res['message']);
            }
        }
    }
}
