<?php

namespace App\Livewire;

use App\Models\UnifiDevice;
use App\Services\SshService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    // Modal State
    public bool $modalForm = false;
    public bool $isEdit = false;
    public ?string $deviceId = null;

    // Form Properties
    public string $name = '';
    public string $ip_address = '';
    public string $ssh_user = '';
    public string $ssh_password = '';
    public ?string $reboot_time = null;
    public bool $is_active = true;

    protected $listeners = [
        'executeReboot' => 'rebootNow',
        'executeDelete' => 'deleteDevice'
    ];

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'ip_address' => 'required|ip',
            'ssh_user' => 'required',
            // Saat edit, password opsional (nullable). Saat tambah baru, password wajib (required).
            'ssh_password' => $this->isEdit ? 'nullable' : 'required',
            'reboot_time' => 'nullable|date_format:H:i',
        ];
    }

    // Buka Modal Tambah Baru
    public function openCreateModal()
    {
        $this->reset(['name', 'ip_address', 'ssh_user', 'ssh_password', 'reboot_time', 'deviceId', 'isEdit']);
        $this->isEdit = false;
        $this->modalForm = true;
    }

    // Buka Modal Edit & Isi Datanya
    public function edit(string $id)
    {
        $this->reset(['name', 'ip_address', 'ssh_user', 'ssh_password', 'reboot_time']);
        $this->isEdit = true;
        $this->deviceId = $id;

        $device = UnifiDevice::findOrFail($id);
        $this->name = $device->name;
        $this->ip_address = $device->ip_address;
        $this->ssh_user = $device->ssh_user;
        $this->reboot_time = $device->reboot_time;
        $this->is_active = $device->is_active;

        $this->modalForm = true;
    }

    // Eksekusi Simpan (Bisa untuk Create maupun Update)
    public function saveDevice()
    {
        if (empty($this->reboot_time)) {
            $this->reboot_time = null;
        }

        $this->validate();

        if ($this->isEdit) {
            // PROSES UPDATE
            $device = UnifiDevice::findOrFail($this->deviceId);

            $data = [
                'name' => $this->name,
                'ip_address' => $this->ip_address,
                'ssh_user' => $this->ssh_user,
                'reboot_time' => $this->reboot_time,
                'is_active' => $this->is_active
            ];

            // Hanya update password jika kolom diisi
            if (!empty($this->ssh_password)) {
                $data['ssh_password'] = $this->ssh_password;
            }

            $device->update($data);

            $message = 'Data perangkat berhasil diperbarui.';
        } else {
            // PROSES TAMBAH BARU
            UnifiDevice::create([
                'name' => $this->name,
                'ip_address' => $this->ip_address,
                'ssh_user' => $this->ssh_user,
                'ssh_password' => $this->ssh_password,
                'reboot_time' => $this->reboot_time,
                'is_active' => $this->is_active
            ]);

            $message = 'Perangkat UniFi berhasil ditambahkan.';
        }

        $this->reset(['name', 'ip_address', 'ssh_user', 'ssh_password', 'reboot_time', 'modalForm', 'deviceId', 'isEdit']);

        $this->dispatch('swal:modal', [
            'title' => 'Sukses!',
            'text' => $message,
            'icon' => 'success'
        ]);
    }

    // Konfirmasi Reboot
    public function confirmReboot(string $id)
    {
        $device = UnifiDevice::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Konfirmasi Reboot',
            'text' => "Apakah Anda yakin ingin me-reboot {$device->name} ({$device->ip_address}) sekarang?",
            'action' => 'executeReboot',
            'id' => $id
        ]);
    }

    // Eksekusi Reboot via SSH
    public function rebootNow(string $id, SshService $ssh)
    {
        $device = UnifiDevice::findOrFail($id);
        $result = $ssh->rebootDevice($device->ip_address, $device->ssh_user, $device->ssh_password);

        if ($result['success']) {
            $device->update(['last_reboot_at' => now()]);
            $this->dispatch('swal:modal', [
                'title' => 'Berhasil!',
                'text' => $result['message'],
                'icon' => 'success'
            ]);
        } else {
            $this->dispatch('swal:modal', [
                'title' => 'Gagal Reboot!',
                'text' => $result['message'],
                'icon' => 'error'
            ]);
        }
    }

    // Konfirmasi Hapus
    public function confirmDelete(string $id)
    {
        $device = UnifiDevice::findOrFail($id);
        $this->dispatch('swal:confirm', [
            'title' => 'Hapus Perangkat?',
            'text' => "Data {$device->name} akan dihapus secara permanen.",
            'action' => 'executeDelete',
            'id' => $id
        ]);
    }

    // Eksekusi Hapus
    public function deleteDevice(string $id)
    {
        UnifiDevice::destroy($id);
        $this->dispatch('swal:modal', [
            'title' => 'Dihapus',
            'text' => 'Perangkat berhasil dihapus dari sistem.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'devices' => UnifiDevice::latest()->get()
        ]);
    }
}