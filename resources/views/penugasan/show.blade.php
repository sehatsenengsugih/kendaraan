<x-app-layout>
    <x-slot name="title">Detail Penugasan</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('penugasan.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Detail Penugasan</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $penugasan->kendaraan->plat_nomor }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if($penugasan->isAktif())
                    <button onclick="document.getElementById('selesai-modal').classList.remove('hidden')"
                        class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Selesaikan
                    </button>
                    <a href="{{ route('penugasan.edit', $penugasan) }}"
                        class="inline-flex items-center rounded-lg border border-bgray-300 px-4 py-2 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Status Penugasan</p>
                        @if($penugasan->status === 'aktif')
                            <span class="inline-flex items-center rounded-full bg-accent-50 px-4 py-2 text-lg font-semibold text-accent-400">
                                <i class="fa fa-play-circle mr-2"></i> Aktif
                            </span>
                        @elseif($penugasan->status === 'selesai')
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-4 py-2 text-lg font-semibold text-blue-600">
                                <i class="fa fa-check-circle mr-2"></i> Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-bgray-100 px-4 py-2 text-lg font-semibold text-bgray-600">
                                <i class="fa fa-times-circle mr-2"></i> Dibatalkan
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Durasi</p>
                        <p class="text-xl font-bold text-bgray-900 dark:text-white">{{ $penugasan->durasi }} hari</p>
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Penugasan</h3>
                <dl class="grid gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Mulai</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $penugasan->tanggal_mulai->format('d M Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Selesai</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $penugasan->tanggal_selesai ? $penugasan->tanggal_selesai->format('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Ditugaskan oleh</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $penugasan->assignedBy->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Dibuat pada</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $penugasan->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>

                @if($penugasan->tujuan)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tujuan Penugasan</dt>
                        <dd class="mt-1 whitespace-pre-line text-bgray-900 dark:text-white">{{ $penugasan->tujuan }}</dd>
                    </div>
                @endif

                @if($penugasan->catatan)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Catatan</dt>
                        <dd class="mt-1 text-bgray-900 dark:text-white">{{ $penugasan->catatan }}</dd>
                    </div>
                @endif
            </div>

            <!-- Pemegang Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Pemegang</h3>
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-accent-50 text-2xl font-bold text-accent-400">
                        {{ strtoupper(substr($penugasan->pemegang->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-bgray-900 dark:text-white">{{ $penugasan->pemegang->name }}</p>
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">{{ $penugasan->pemegang->lembaga ?? ucfirst($penugasan->pemegang->type) }}</p>
                        @if($penugasan->pemegang->email)
                            <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-envelope mr-1"></i> {{ $penugasan->pemegang->email }}
                            </p>
                        @endif
                        @if($penugasan->pemegang->phone)
                            <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                <i class="fa fa-phone mr-1"></i> {{ $penugasan->pemegang->phone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Kendaraan Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Kendaraan</h3>
                <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-bgray-100">
                    @if($penugasan->kendaraan->avatar_path)
                        <img src="{{ asset('storage/' . $penugasan->kendaraan->avatar_path) }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-bgray-400">
                            <i class="fa {{ $penugasan->kendaraan->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }} text-4xl"></i>
                        </div>
                    @endif
                </div>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Plat Nomor</dt>
                        <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $penugasan->kendaraan->plat_nomor }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kendaraan</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $penugasan->kendaraan->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Garasi</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $penugasan->kendaraan->garasi->nama ?? '-' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('kendaraan.show', $penugasan->kendaraan) }}"
                    class="mt-4 block text-center text-sm font-semibold text-accent-300 hover:underline">
                    Lihat Detail Kendaraan
                </a>
            </div>

            <!-- Delete Button -->
            @if($penugasan->isAktif())
                <form method="POST" action="{{ route('penugasan.destroy', $penugasan) }}"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-lg border border-error-300 px-4 py-3 font-semibold text-error-300 hover:bg-error-50">
                        <i class="fa fa-trash mr-2"></i> Hapus Penugasan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Selesai Modal -->
    @if($penugasan->isAktif())
    <div id="selesai-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-darkblack-600">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Selesaikan Penugasan</h3>
                <button onclick="document.getElementById('selesai-modal').classList.add('hidden')"
                    class="text-bgray-400 hover:text-bgray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('penugasan.selesai', $penugasan) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Selesai <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ now()->format('Y-m-d') }}" required
                            min="{{ $penugasan->tanggal_mulai->format('Y-m-d') }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Catatan</label>
                        <textarea name="catatan" rows="3"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                            placeholder="Catatan penyelesaian...">{{ $penugasan->catatan }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-accent-300 py-3 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Selesaikan
                    </button>
                    <button type="button" onclick="document.getElementById('selesai-modal').classList.add('hidden')"
                        class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-app-layout>
