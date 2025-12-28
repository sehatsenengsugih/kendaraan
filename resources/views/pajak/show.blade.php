<x-app-layout>
    <x-slot name="title">Detail Pajak</x-slot>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('pajak.index') }}"
                    class="rounded-lg p-2 text-bgray-600 hover:bg-bgray-100 dark:text-bgray-50 dark:hover:bg-darkblack-500">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Detail Pajak</h2>
                    <p class="text-sm text-bgray-600 dark:text-bgray-50">{{ $pajak->kendaraan->plat_nomor }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if($pajak->status !== 'lunas')
                    <button onclick="document.getElementById('bayar-modal').classList.remove('hidden')"
                        class="inline-flex items-center rounded-lg bg-accent-300 px-4 py-2 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Bayar Pajak
                    </button>
                    <a href="{{ route('pajak.edit', $pajak) }}"
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
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Status Pajak</p>
                        @if($pajak->status === 'lunas')
                            <span class="inline-flex items-center rounded-full bg-accent-50 px-4 py-2 text-lg font-semibold text-accent-400">
                                <i class="fa fa-check-circle mr-2"></i> Lunas
                            </span>
                        @elseif($pajak->isOverdue())
                            <span class="inline-flex items-center rounded-full bg-error-50 px-4 py-2 text-lg font-semibold text-error-300">
                                <i class="fa fa-times-circle mr-2"></i> Terlambat {{ abs($pajak->days_until_due) }} hari
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-warning-50 px-4 py-2 text-lg font-semibold text-warning-400">
                                <i class="fa fa-clock mr-2"></i> Jatuh tempo {{ $pajak->days_until_due }} hari lagi
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-bgray-500 dark:text-bgray-50">Jatuh Tempo</p>
                        <p class="text-xl font-bold text-bgray-900 dark:text-white">{{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Informasi Pajak</h3>
                <dl class="grid gap-4 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Jenis Pajak</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $pajak->jenis === 'tahunan' ? 'Pajak Tahunan' : 'Pajak 5 Tahunan (Ganti Plat)' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Tanggal Bayar</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $pajak->tanggal_bayar ? $pajak->tanggal_bayar->format('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Nominal Pajak</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $pajak->nominal ? 'Rp ' . number_format($pajak->nominal, 0, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Denda</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">
                            {{ $pajak->denda ? 'Rp ' . number_format($pajak->denda, 0, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Total</dt>
                        <dd class="text-xl font-bold text-accent-400">
                            Rp {{ number_format($pajak->total, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Nomor Notice</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $pajak->nomor_notice ?? '-' }}</dd>
                    </div>
                </dl>

                @if($pajak->catatan)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Catatan</dt>
                        <dd class="mt-1 text-bgray-900 dark:text-white">{{ $pajak->catatan }}</dd>
                    </div>
                @endif

                @if($pajak->bukti_path)
                    <div class="mt-4 border-t border-bgray-200 pt-4 dark:border-darkblack-400">
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Bukti Bayar</dt>
                        <dd class="mt-2">
                            <a href="{{ $pajak->bukti_url }}" target="_blank"
                                class="inline-flex items-center text-accent-300 hover:underline">
                                <i class="fa fa-file-image mr-2"></i> Lihat Bukti
                            </a>
                        </dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Kendaraan Info -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <h3 class="mb-4 text-lg font-semibold text-bgray-900 dark:text-white">Kendaraan</h3>
                <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-bgray-100">
                    @if($pajak->kendaraan->avatar_path)
                        <img src="{{ asset('storage/' . $pajak->kendaraan->avatar_path) }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-bgray-400">
                            <i class="fa {{ $pajak->kendaraan->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car' }} text-4xl"></i>
                        </div>
                    @endif
                </div>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Plat Nomor</dt>
                        <dd class="font-mono font-medium text-bgray-900 dark:text-white">{{ $pajak->kendaraan->plat_nomor }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Kendaraan</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $pajak->kendaraan->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-bgray-500 dark:text-bgray-50">Garasi</dt>
                        <dd class="font-medium text-bgray-900 dark:text-white">{{ $pajak->kendaraan->garasi->nama ?? '-' }}</dd>
                    </div>
                </dl>
                <a href="{{ route('kendaraan.show', $pajak->kendaraan) }}"
                    class="mt-4 block text-center text-sm font-semibold text-accent-300 hover:underline">
                    Lihat Detail Kendaraan
                </a>
            </div>

            <!-- Timestamp -->
            <div class="rounded-lg bg-white p-6 dark:bg-darkblack-600">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat oleh</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $pajak->createdBy->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-bgray-500 dark:text-bgray-50">Dibuat</dt>
                        <dd class="text-bgray-900 dark:text-white">{{ $pajak->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Bayar Modal -->
    @if($pajak->status !== 'lunas')
    <div id="bayar-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-darkblack-600">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-bgray-900 dark:text-white">Bayar Pajak</h3>
                <button onclick="document.getElementById('bayar-modal').classList.add('hidden')"
                    class="text-bgray-400 hover:text-bgray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('pajak.bayar', $pajak) }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">
                            Tanggal Bayar <span class="text-error-300">*</span>
                        </label>
                        <input type="date" name="tanggal_bayar" value="{{ now()->format('Y-m-d') }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Nominal (Rp)</label>
                        <input type="number" name="nominal" value="{{ $pajak->nominal }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Denda (Rp)</label>
                        <input type="number" name="denda" value="{{ $pajak->denda }}" min="0"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Nomor Notice</label>
                        <input type="text" name="nomor_notice" value="{{ $pajak->nomor_notice }}"
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-bgray-900 dark:text-white">Upload Bukti</label>
                        <input type="file" name="bukti" accept="image/*,.pdf"
                            class="w-full text-sm text-bgray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-accent-300 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-accent-300 py-3 font-semibold text-white hover:bg-accent-400">
                        <i class="fa fa-check mr-2"></i> Tandai Lunas
                    </button>
                    <button type="button" onclick="document.getElementById('bayar-modal').classList.add('hidden')"
                        class="rounded-lg border border-bgray-300 px-6 py-3 font-semibold text-bgray-600 hover:bg-bgray-100 dark:border-darkblack-400 dark:text-bgray-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-app-layout>
