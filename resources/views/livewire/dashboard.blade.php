<div>
    {{-- HEADER --}}
    <x-header title="UniFi Controller Dashboard" subtitle="Kelola dan atur jadwal reboot perangkat secara otomatis">
        <x-slot:actions>
            <x-button label="Tambah Perangkat" wire:click="openCreateModal" icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- TABEL DAFTAR PERANGKAT --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Nama Perangkat</th>
                        <th>Alamat IP</th>
                        <th>User SSH</th>
                        <th>Jadwal Otomatis</th>
                        <th>Reboot Terakhir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                        <tr class="hover">
                            <td class="font-bold">{{ $device->name }}</td>
                            <td><code>{{ $device->ip_address }}</code></td>
                            <td>{{ $device->ssh_user }}</td>
                            <td>
                                @if ($device->reboot_time)
                                    <span class="badge badge-info gap-1">⏰ {{ $device->reboot_time }}</span>
                                @else
                                    <span class="badge badge-ghost text-gray-400">Manual Only</span>
                                @endif
                            </td>
                            <td>{{ $device->last_reboot_at ? $device->last_reboot_at->diffForHumans() : 'Belum pernah' }}
                            </td>
                            <td class="text-center space-x-1">
                                {{-- Tombol Reboot --}}
                                <button wire:click="confirmReboot('{{ $device->id }}')" class="btn btn-warning btn-sm"
                                    title="Reboot Sekarang">
                                    ⚡ Reboot
                                </button>

                                {{-- Tombol Edit --}}
                                <button wire:click="edit('{{ $device->id }}')" class="btn btn-info btn-sm btn-outline"
                                    title="Edit Data">
                                    ✏️ Edit
                                </button>

                                {{-- Tombol Hapus --}}
                                <button wire:click="confirmDelete('{{ $device->id }}')"
                                    class="btn btn-error btn-sm btn-outline" title="Hapus Perangkat">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                Belum ada perangkat terdaftar. Silakan tambahkan perangkat baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- MODAL FORM (BISA UNTUK TAMBAH MAUPUN EDIT) --}}
    <x-modal wire:model="modalForm" title="{{ $isEdit ? 'Edit Perangkat UniFi' : 'Tambah Perangkat UniFi Baru' }}"
        class="backdrop-blur">
        <div class="space-y-4">
            <x-input label="Nama Perangkat" wire:model="name" placeholder="AP-Lt2-Lobby" />
            <x-input label="IP Address" wire:model="ip_address" placeholder="192.168.100.X" />
            <x-input label="Username SSH" wire:model="ssh_user" placeholder="admin / ubnt" />

            {{-- Input Password dengan keterangan jika sedang edit --}}
            <x-input label="Password SSH" type="password" wire:model="ssh_password"
                hint="{{ $isEdit ? 'Kosongkan jika tidak ingin mengganti password lama.' : '' }}" />

            {{-- Input Waktu Native --}}
            <div>
                <x-input label="Jadwal Reboot Otomatis (Opsional)" type="time" wire:model="reboot_time"
                    hint="Format 24 jam. Kosongkan jika hanya ingin manual." />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.modalForm = false" />
            <x-button label="{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Perangkat' }}" wire:click="saveDevice"
                class="btn-primary" spinner="saveDevice" />
        </x-slot:actions>
    </x-modal>
</div>
