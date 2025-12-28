<x-app-layout>
    <x-slot name="title">Detail Servis</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('servis.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Detail Servis</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $servis->kendaraan->plat_nomor ?? '-' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if(!$servis->isSelesai() && $servis->status !== 'dibatalkan')
                    <button onclick="document.getElementById('selesai-modal').classList.remove('hidden')"
                        class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Tandai Selesai
                    </button>
                    <a href="{{ route('servis.edit', $servis) }}"
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
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Status Servis</p>
                        @if($servis->status === 'selesai')
                            <span class="inline-flex items-center rounded-full bg-accent-50 px-4 py-2 text-lg font-semibold text-accent-400">
                                <i class="fa fa-check-circle mr-2"></i> Selesai
                            </span>
                        @elseif($servis->status === 'dalam_proses')
                            <span class="inline-flex items-center rounded-full bg-warning-50 px-4 py-2 text-lg font-semibold text-warning-400">
                                <i class="fa fa-tools mr-2"></i> Dalam Proses
                            </span>
                        @elseif($servis->status === 'dijadwalkan')
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-4 py-2 text-lg font-semibold text-blue-600">
                                <i class="fa fa-calendar-alt mr-2"></i> Dijadwalkan
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-bgray-100 px-4 py-2 text-lg font-semibold text-bgray-600">
                                <i class="fa fa-times-circle mr-2"></i> Dibatalkan
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Jenis Servis</p>
                        @if($servis->jenis === 'rutin')
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-600">
                                Servis Rutin
                            </span>
                        @elseif($servis->jenis === 'perbaikan')
                            <span class="inline-flex rounded-full bg-yellow-50 px-3 py-1 text-sm font-medium text-yellow-600">
                                Perbaikan
                            </span>
                        @elseif($servis->jenis === 'darurat')
                            <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600">
                                Perbaikan Darurat
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-sm font-medium text-purple-600">
                                Overhaul
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Servis</h3>
                <dl class="grid gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Servis</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $servis->tanggal_servis->format('d M Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Selesai</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $servis->tanggal_selesai ? $servis->tanggal_selesai->format('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Bengkel</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $servis->bengkel ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kilometer</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $servis->kilometer ? number_format($servis->kilometer, 0, ',', '.') . ' km' : '-' }}
                        </dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Biaya</dt>
                        <dd class="text-xl font-bold text-accent-400">
                            {{ $servis->biaya ? $servis->biaya_formatted : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Deskripsi & Spare Parts -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Deskripsi Pekerjaan</h3>
                <p class="whitespace-pre-line text-bgray-700 dark:text-bgray-100">{{ $servis->deskripsi }}</p>

                @if($servis->spare_parts)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <h4 class="mb-2 font-medium text-bgray-900 dark:text-white">Spare Parts / Komponen</h4>
                        <p class="whitespace-pre-line text-bgray-700 dark:text-bgray-100">{{ $servis->spare_parts }}</p>
                    </div>
                @endif

                @if($servis->catatan)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <h4 class="mb-2 font-medium text-bgray-900 dark:text-white">Catatan</h4>
                        <p class="text-bgray-700 dark:text-bgray-100">{{ $servis->catatan }}</p>
                    </div>
                @endif

                @if($servis->bukti_path)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <h4 class="mb-2 font-medium text-bgray-900 dark:text-white">Bukti / Nota</h4>
                        <a href="{{ $servis->bukti_url }}" target="_blank"
                            class="inline-flex items-center text-accent-300 hover:underline">
                            <i class="fa fa-file-image mr-2"></i> Lihat Bukti
                        </a>
                    </div>
                @endif
            </div>

            <!-- Next Service Info -->
            @if($servis->servis_berikutnya || $servis->km_berikutnya)
                <div class="rounded-lg bg-blue-50 p-6 dark:bg-blue-900/20">
                    <h3 class="mb-4 text-lg font-semibold text-blue-900 dark:text-blue-100">
                        <i class="fa fa-calendar-alt mr-2"></i> Jadwal Servis Berikutnya
                    </h3>
                    <dl class="grid gap-4 md:grid-cols-2">
                        @if($servis->servis_berikutnya)
                            <div>
                                <dt class="text-sm text-blue-700 dark:text-blue-200">Tanggal</dt>
                                <dd class="font-medium text-blue-900 dark:text-white">
                                    {{ $servis->servis_berikutnya->format('d M Y') }}
                                    @if($servis->days_until_next_service !== null)
                                        @if($servis->days_until_next_service < 0)
                                            <span class="ml-2 text-sm text-red-600">({{ abs($servis->days_until_next_service) }} hari terlewat)</span>
                                        @elseif($servis->days_until_next_service <= 30)
                                            <span class="ml-2 text-sm text-orange-600">({{ $servis->days_until_next_service }} hari lagi)</span>
                                        @else
                                            <span class="ml-2 text-sm text-green-600">({{ $servis->days_until_next_service }} hari lagi)</span>
                                        @endif
                                    @endif
                                </dd>
                            </div>
                        @endif
                        @if($servis->km_berikutnya)
                            <div>
                                <dt class="text-sm text-blue-700 dark:text-blue-200">Kilometer</dt>
                                <dd class="font-medium text-blue-900 dark:text-white">
                                    {{ number_format($servis->km_berikutnya, 0, ',', '.') }} km
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Kendaraan Info -->
            @if($servis->kendaraan)
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Kendaraan</h3>
                <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-bgray-100">
                    @if($servis->kendaraan->avatar_path)
                        <img src="{{ asset('storage/' . $servis->kendaraan->avatar_path) }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-bgray-400">
                            <i class="fa {{ $servis->kendaraan->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }} text-4xl"></i>
                        </div>
                    @endif
                </div>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Plat Nomor</dt>
                        <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $servis->kendaraan->plat_nomor }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kendaraan</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $servis->kendaraan->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Garasi</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $servis->kendaraan->garasi->nama ?? '-' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('kendaraan.show', $servis->kendaraan) }}"
                    class="mt-4 block text-center text-sm font-semibold text-accent-300 hover:underline">
                    Lihat Detail Kendaraan
                </a>
            </div>
            @else
            <div class="rounded-lg bg-warning-50 p-6 dark:bg-warning-900/20">
                <h3 class="mb-2 text-lg font-semibold text-warning-600">Kendaraan Tidak Ditemukan</h3>
                <p class="text-sm text-warning-500">Data kendaraan untuk servis ini tidak ditemukan atau telah dihapus.</p>
            </div>
            @endif

            <!-- Timestamp -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat oleh</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $servis->createdBy->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $servis->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Delete Button -->
            @if(!$servis->isSelesai())
                <form method="POST" action="{{ route('servis.destroy', $servis) }}"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data servis ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full rounded-lg border border-error-300 px-4 py-3 font-semibold text-error-300 hover:bg-error-50">
                        <i class="fa fa-trash mr-2"></i> Hapus Servis
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Selesai Modal -->
    @if(!$servis->isSelesai() && $servis->status !== 'dibatalkan')
    <div id="selesai-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-darkblack-600">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Tandai Servis Selesai</h3>
                <button onclick="document.getElementById('selesai-modal').classList.add('hidden')"
                    class="text-bgray-400 hover:text-bgray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('servis.selesai', $servis) }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Selesai <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ now()->format('Y-m-d') }}" required
                            min="{{ $servis->tanggal_servis->format('Y-m-d') }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Biaya (Rp)</label>
                        <input type="number" name="biaya" value="{{ $servis->biaya }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Upload Bukti / Nota</label>
                        <input type="file" name="bukti" accept="image/*,.pdf"
                            class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-accent-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    </div>
                    <div class="rounded-lg border border-bgray-200 p-4 dark:border-darkblack-400">
                        <h4 class="mb-3 text-sm font-medium text-bgray-900 dark:text-white">Jadwal Servis Berikutnya</h4>
                        <div class="grid gap-3 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs text-bgray-500">Tanggal</label>
                                <input type="date" name="servis_berikutnya" value="{{ $servis->servis_berikutnya?->format('Y-m-d') }}"
                                    class="w-full rounded-lg border border-bgray-200 px-3 py-2 text-sm text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-bgray-500">Kilometer</label>
                                <input type="number" name="km_berikutnya" value="{{ $servis->km_berikutnya }}" min="0"
                                    class="w-full rounded-lg border border-bgray-200 px-3 py-2 text-sm text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-accent-300 py-3 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Tandai Selesai
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
