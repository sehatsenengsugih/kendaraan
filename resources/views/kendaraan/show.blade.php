<x-app-layout>
    <x-slot name="title">Detail Kendaraan</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('kendaraan.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">{{ $kendaraan->display_name }}</h2>
                    <p class="font-mono text-sm text-bgray-600 dark:text-bgray-50">{{ $kendaraan->plat_nomor }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kendaraan.edit', $kendaraan) }}"
                    class="inline-flex items-center rounded-lg border border-bgray-300 px-4 py-2 font-semibold text-bgray-600 transition-all hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-edit mr-2"></i> Edit
                </a>
                <form method="POST" action="{{ route('kendaraan.destroy', $kendaraan) }}"
                    onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center rounded-lg border border-error-300 px-4 py-2 font-semibold text-error-300 transition-all hover:bg-error-50">
                        <i class="fa fa-trash mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Photo Gallery -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-bgray-100">
                    @if($kendaraan->avatar_path)
                        <img src="{{ asset('storage/' . $kendaraan->avatar_path) }}"
                            alt="{{ $kendaraan->display_name }}"
                            class="h-full w-full object-cover"
                            id="main-image">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-bgray-400">
                            <i class="fa {{ $kendaraan->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }} text-6xl"></i>
                        </div>
                    @endif
                </div>

                @if($kendaraan->gambar->count() > 0)
                    <div class="grid grid-cols-6 gap-2">
                        @if($kendaraan->avatar_path)
                            <button type="button" onclick="changeMainImage('{{ asset('storage/' . $kendaraan->avatar_path) }}')"
                                class="aspect-square overflow-hidden rounded-lg border-2 border-success-300 bg-bgray-100">
                                <img src="{{ asset('storage/' . $kendaraan->avatar_path) }}" class="h-full w-full object-cover">
                            </button>
                        @endif
                        @foreach($kendaraan->gambar as $gambar)
                            <button type="button" onclick="changeMainImage('{{ $gambar->url }}')"
                                class="aspect-square overflow-hidden rounded-lg border-2 border-transparent bg-bgray-100 hover:border-success-300">
                                <img src="{{ $gambar->url }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Details Grid -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Spesifikasi -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Spesifikasi</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Jenis</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">
                                @if($kendaraan->jenis === 'mobil')
                                    <i class="fa fa-car mr-1 text-blue-500"></i> Mobil
                                @else
                                    <i class="fa fa-motorcycle mr-1 text-orange-500"></i> Motor
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Merk</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->merk->nama ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Model</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nama_model }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Tahun</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tahun_pembuatan }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Warna</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->warna }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Dokumen -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Dokumen</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Plat Nomor</dt>
                            <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $kendaraan->plat_nomor }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">No. BPKB</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nomor_bpkb }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Tgl Perolehan</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_perolehan?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        @if($kendaraan->tanggal_hibah)
                            <div class="flex justify-between">
                                <dt class="text-bgray-500 dark:text-bgray-50">Tgl Hibah</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_hibah->format('d M Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Catatan -->
            @if($kendaraan->catatan)
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Catatan</h3>
                    <p class="text-bgray-600 dark:text-bgray-50">{{ $kendaraan->catatan }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <div class="mb-4 text-center">
                    @if($kendaraan->status === 'aktif')
                        <span class="inline-flex items-center rounded-full bg-success-50 px-4 py-2 text-lg font-semibold text-success-400">
                            <i class="fa fa-check-circle mr-2"></i> Aktif
                        </span>
                    @elseif($kendaraan->status === 'nonaktif')
                        <span class="inline-flex items-center rounded-full bg-warning-50 px-4 py-2 text-lg font-semibold text-warning-400">
                            <i class="fa fa-pause-circle mr-2"></i> Non-Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-bgray-100 px-4 py-2 text-lg font-semibold text-bgray-600">
                            <i class="fa fa-gift mr-2"></i> Dihibahkan
                        </span>
                    @endif
                </div>
            </div>

            <!-- Lokasi -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Lokasi</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Garasi</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            <a href="{{ route('garasi.show', $kendaraan->garasi) }}" class="hover:text-success-300">
                                {{ $kendaraan->garasi->nama ?? '-' }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kevikepan</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $kendaraan->garasi->kevikepan->nama ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kota</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $kendaraan->garasi->kota ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Pemegang -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Pemegang</h3>
                @if($kendaraan->pemegang)
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-50 text-success-400">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <p class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->pemegang->name }}</p>
                            <p class="text-sm text-bgray-500 dark:text-bgray-50">
                                {{ $kendaraan->pemegang->organization_name ?? ucfirst($kendaraan->pemegang->user_type) }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-bgray-500 dark:text-bgray-50">Tidak ada pemegang</p>
                @endif
            </div>

            <!-- Timestamp -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $kendaraan->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Diperbarui</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $kendaraan->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function changeMainImage(src) {
            document.getElementById('main-image').src = src;
        }
    </script>
    @endpush
</x-app-layout>
