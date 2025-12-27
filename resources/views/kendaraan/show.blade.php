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

                <!-- Dokumen & Identitas -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Dokumen & Identitas</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Plat Nomor</dt>
                            <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $kendaraan->plat_nomor }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">BPKB</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">
                                @if($kendaraan->ada_bpkb)
                                    <span class="text-success-400"><i class="fa fa-check-circle mr-1"></i> Ada</span>
                                    @if($kendaraan->nomor_bpkb)
                                        <span class="block text-sm font-mono">{{ $kendaraan->nomor_bpkb }}</span>
                                    @endif
                                @else
                                    <span class="text-bgray-400">Tidak Ada</span>
                                @endif
                            </dd>
                        </div>
                        @if($kendaraan->nomor_rangka)
                            <div class="flex justify-between">
                                <dt class="text-bgray-500 dark:text-bgray-50">No. Rangka</dt>
                                <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nomor_rangka }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->nomor_mesin)
                            <div class="flex justify-between">
                                <dt class="text-bgray-500 dark:text-bgray-50">No. Mesin</dt>
                                <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nomor_mesin }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Kepemilikan & Perolehan -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Kepemilikan -->
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Kepemilikan</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Status</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">
                                @if($kendaraan->status_kepemilikan === 'milik_kas')
                                    <span class="inline-flex items-center rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-400">
                                        Milik KAS
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600">
                                        Milik Lembaga Lain
                                    </span>
                                @endif
                            </dd>
                        </div>
                        @if($kendaraan->status_kepemilikan === 'milik_lembaga_lain' && $kendaraan->nama_pemilik_lembaga)
                            <div class="flex justify-between">
                                <dt class="text-bgray-500 dark:text-bgray-50">Nama Lembaga</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nama_pemilik_lembaga }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-bgray-500 dark:text-bgray-50">Tgl Perolehan</dt>
                            <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_perolehan?->format('d M Y') ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Info Pembelian -->
                @if($kendaraan->tanggal_beli || $kendaraan->harga_beli)
                    <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                        <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Info Pembelian</h3>
                        <dl class="space-y-3">
                            @if($kendaraan->tanggal_beli)
                                <div class="flex justify-between">
                                    <dt class="text-bgray-500 dark:text-bgray-50">Tanggal Beli</dt>
                                    <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_beli->format('d M Y') }}</dd>
                                </div>
                            @endif
                            @if($kendaraan->harga_beli)
                                <div class="flex justify-between">
                                    <dt class="text-bgray-500 dark:text-bgray-50">Harga Beli</dt>
                                    <dd class="font-medium text-bgray-900 dark:text-white">Rp {{ number_format($kendaraan->harga_beli, 0, ',', '.') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>

            <!-- Info Hibah (if status=dihibahkan) -->
            @if($kendaraan->status === 'dihibahkan')
                <div class="rounded-lg bg-bgray-50 p-6 dark:bg-darkblack-500">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-gift mr-2 text-purple-500"></i> Info Hibah
                    </h3>
                    <dl class="grid gap-4 md:grid-cols-2">
                        @if($kendaraan->tanggal_hibah)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Hibah</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_hibah->format('d M Y') }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->nama_penerima_hibah)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Penerima Hibah</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nama_penerima_hibah }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Info Penjualan (if status=dijual) -->
            @if($kendaraan->status === 'dijual')
                <div class="rounded-lg bg-orange-50 p-6 dark:bg-darkblack-500">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-money-bill mr-2 text-orange-500"></i> Info Penjualan
                    </h3>
                    <dl class="grid gap-4 md:grid-cols-3">
                        @if($kendaraan->tanggal_jual)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Jual</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_jual->format('d M Y') }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->harga_jual)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Harga Jual</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">Rp {{ number_format($kendaraan->harga_jual, 0, ',', '.') }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->nama_pembeli)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Nama Pembeli</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->nama_pembeli }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Status Pinjam -->
            @if($kendaraan->is_dipinjam)
                <div class="rounded-lg bg-yellow-50 p-6 dark:bg-darkblack-500">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-handshake mr-2 text-yellow-500"></i> Sedang Dipinjam
                    </h3>
                    <dl class="grid gap-4 md:grid-cols-2">
                        @if($kendaraan->dipinjam_oleh)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Dipinjam Oleh</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->dipinjam_oleh }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->tanggal_pinjam)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Pinjam</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tanggal_pinjam->format('d M Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Info Tarikan -->
            @if($kendaraan->is_tarikan)
                <div class="rounded-lg bg-red-50 p-6 dark:bg-darkblack-500">
                    <h3 class="mb-4 flex items-center text-lg font-semibold text-bgray-900 dark:text-white">
                        <i class="fa fa-truck-pickup mr-2 text-red-500"></i> Kendaraan Tarikan
                    </h3>
                    <dl class="grid gap-4 md:grid-cols-2">
                        @if($kendaraan->tarikan_dari)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Ditarik Dari</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tarikan_dari }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->tarikan_pemakai)
                            <div>
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Pemakai Sebelumnya</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tarikan_pemakai }}</dd>
                            </div>
                        @endif
                        @if($kendaraan->tarikan_kondisi)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kondisi Saat Ditarik</dt>
                                <dd class="font-medium text-bgray-900 dark:text-white">{{ $kendaraan->tarikan_kondisi }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Riwayat Pemakai -->
            @if($kendaraan->riwayatPemakai->count() > 0)
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Riwayat Pemakai</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-bgray-200 dark:border-darkblack-400">
                                <tr>
                                    <th class="py-3 font-semibold text-bgray-900 dark:text-white">Nama Pemakai</th>
                                    <th class="py-3 font-semibold text-bgray-900 dark:text-white">Jenis</th>
                                    <th class="py-3 font-semibold text-bgray-900 dark:text-white">Periode</th>
                                    <th class="py-3 font-semibold text-bgray-900 dark:text-white">Durasi</th>
                                    <th class="py-3 font-semibold text-bgray-900 dark:text-white">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-bgray-100 dark:divide-darkblack-400">
                                @foreach($kendaraan->riwayatPemakai as $riwayat)
                                    <tr>
                                        <td class="py-3 text-bgray-900 dark:text-white">
                                            {{ $riwayat->nama_pemakai }}
                                            @if($riwayat->catatan)
                                                <p class="text-xs text-bgray-500 mt-1">{{ Str::limit($riwayat->catatan, 50) }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 text-bgray-600 dark:text-bgray-50">
                                            @if($riwayat->jenis_pemakai === 'lembaga')
                                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600">
                                                    Lembaga
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-0.5 text-xs font-medium text-purple-600">
                                                    Pribadi
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-bgray-600 dark:text-bgray-50">
                                            {{ $riwayat->tanggal_mulai->format('d M Y') }}
                                            @if($riwayat->tanggal_selesai)
                                                - {{ $riwayat->tanggal_selesai->format('d M Y') }}
                                            @else
                                                - sekarang
                                            @endif
                                        </td>
                                        <td class="py-3 text-bgray-600 dark:text-bgray-50">
                                            {{ $riwayat->durasi }} hari
                                        </td>
                                        <td class="py-3">
                                            @if($riwayat->isAktif())
                                                <span class="inline-flex items-center rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-400">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-bgray-100 px-2 py-0.5 text-xs font-medium text-bgray-600">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Catatan -->
            @if($kendaraan->catatan)
                <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                    <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Catatan</h3>
                    <p class="text-bgray-600 dark:text-bgray-50 whitespace-pre-line">{{ $kendaraan->catatan }}</p>
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
                    @elseif($kendaraan->status === 'dihibahkan')
                        <span class="inline-flex items-center rounded-full bg-purple-50 px-4 py-2 text-lg font-semibold text-purple-600">
                            <i class="fa fa-gift mr-2"></i> Dihibahkan
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-orange-50 px-4 py-2 text-lg font-semibold text-orange-600">
                            <i class="fa fa-money-bill mr-2"></i> Dijual
                        </span>
                    @endif
                </div>

                <!-- Additional status badges -->
                <div class="flex flex-wrap justify-center gap-2">
                    @if($kendaraan->is_dipinjam)
                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-sm font-medium text-yellow-600">
                            <i class="fa fa-handshake mr-1"></i> Dipinjam
                        </span>
                    @endif
                    @if($kendaraan->is_tarikan)
                        <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-sm font-medium text-red-600">
                            <i class="fa fa-truck-pickup mr-1"></i> Tarikan
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
