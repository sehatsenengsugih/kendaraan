<x-app-layout title="Kalender">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Kalender Kendaraan</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Jadwal pajak dan servis kendaraan</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="openAddModal('pajak')"
                    class="inline-flex items-center rounded-lg bg-warning-300 px-4 py-2 text-sm font-medium text-white hover:bg-warning-400 transition-colors">
                    <i class="fa fa-plus mr-2"></i>Tambah Pajak
                </button>
                <button type="button" onclick="openAddModal('servis')"
                    class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                    <i class="fa fa-plus mr-2"></i>Tambah Servis
                </button>
            </div>
        </div>
    </x-slot>

    <div class="card">
        <!-- Legend -->
        <div class="mb-4 flex flex-wrap gap-4 border-b border-bgray-200 dark:border-darkblack-400 pb-4">
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #EF4444;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Pajak Terlambat</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #F97316;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Pajak ≤7 Hari</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #EAB308;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Pajak ≤30 Hari</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #22C55E;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Pajak Lunas</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #3B82F6;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Servis Dijadwalkan</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #8B5CF6;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Servis Dalam Proses</span>
            </span>
            <span class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background-color: #6366F1;"></span>
                <span class="text-sm text-bgray-600 dark:text-bgray-300">Servis Berikutnya</span>
            </span>
        </div>

        <!-- Calendar Container -->
        <div id="calendar"></div>
    </div>

    <!-- Event Modal -->
    <div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

            <div class="relative inline-block w-full max-w-lg transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-darkblack-600 sm:my-8 sm:align-middle">
                <div class="border-b border-bgray-200 dark:border-darkblack-400 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 id="modalTitle" class="text-lg font-semibold text-bgray-900 dark:text-white">Tambah Event</h3>
                        <button type="button" onclick="closeModal()" class="text-bgray-400 hover:text-bgray-600 dark:hover:text-white">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <form id="eventForm" onsubmit="submitForm(event)">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Event Type Selector (for add mode) -->
                        <div id="typeSelector">
                            <label class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-2">Tipe Event</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="event_type" value="pajak" checked onchange="switchEventType('pajak')"
                                        class="mr-2 text-warning-300 focus:ring-warning-300">
                                    <span class="text-bgray-700 dark:text-bgray-300">Pajak</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="event_type" value="servis" onchange="switchEventType('servis')"
                                        class="mr-2 text-blue-500 focus:ring-blue-500">
                                    <span class="text-bgray-700 dark:text-bgray-300">Servis</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kendaraan Select -->
                        <div>
                            <label for="kendaraan_id" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                                Kendaraan <span class="text-error-300">*</span>
                            </label>
                            <select name="kendaraan_id" id="kendaraan_id" required
                                class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                <option value="">Pilih Kendaraan</option>
                                @foreach($kendaraanList as $kendaraan)
                                    <option value="{{ $kendaraan->id }}">
                                        {{ $kendaraan->plat_nomor }} - {{ $kendaraan->merk->nama ?? '' }} {{ $kendaraan->nama_model ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pajak Fields -->
                        <div id="pajakFields">
                            <div class="mb-4">
                                <label for="pajak_jenis" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                                    Jenis Pajak <span class="text-error-300">*</span>
                                </label>
                                <select name="pajak_jenis" id="pajak_jenis"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    <option value="tahunan">Pajak Tahunan</option>
                                    <option value="lima_tahunan">Pajak 5 Tahunan (Ganti Plat)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_jatuh_tempo" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                                    Tanggal Jatuh Tempo <span class="text-error-300">*</span>
                                </label>
                                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            </div>

                            <div class="mb-4">
                                <label for="pajak_nominal" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nominal</label>
                                <input type="number" name="pajak_nominal" id="pajak_nominal" min="0" step="1000"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                    placeholder="0">
                            </div>

                            <div id="pajakStatusField" class="mb-4 hidden">
                                <label for="pajak_status" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Status</label>
                                <select name="pajak_status" id="pajak_status"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="terlambat">Terlambat</option>
                                </select>
                            </div>

                            <div>
                                <label for="pajak_catatan" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Catatan</label>
                                <textarea name="pajak_catatan" id="pajak_catatan" rows="2"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                    placeholder="Catatan tambahan (opsional)"></textarea>
                            </div>
                        </div>

                        <!-- Servis Fields -->
                        <div id="servisFields" class="hidden">
                            <div class="mb-4">
                                <label for="servis_jenis" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                                    Jenis Servis <span class="text-error-300">*</span>
                                </label>
                                <select name="servis_jenis" id="servis_jenis"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    <option value="rutin">Servis Rutin</option>
                                    <option value="perbaikan">Perbaikan</option>
                                    <option value="darurat">Perbaikan Darurat</option>
                                    <option value="overhaul">Overhaul</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="tanggal_servis" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">
                                    Tanggal Servis <span class="text-error-300">*</span>
                                </label>
                                <input type="date" name="tanggal_servis" id="tanggal_servis"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                            </div>

                            <div class="mb-4">
                                <label for="servis_bengkel" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Bengkel</label>
                                <input type="text" name="servis_bengkel" id="servis_bengkel"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                    placeholder="Nama bengkel">
                            </div>

                            <div class="mb-4">
                                <label for="servis_biaya" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Estimasi Biaya</label>
                                <input type="number" name="servis_biaya" id="servis_biaya" min="0" step="1000"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                    placeholder="0">
                            </div>

                            <div id="servisStatusField" class="mb-4 hidden">
                                <label for="servis_status" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Status</label>
                                <select name="servis_status" id="servis_status"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                                    <option value="dijadwalkan">Dijadwalkan</option>
                                    <option value="dalam_proses">Dalam Proses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div>
                                <label for="servis_deskripsi" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Deskripsi</label>
                                <textarea name="servis_deskripsi" id="servis_deskripsi" rows="2"
                                    class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-success-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white"
                                    placeholder="Deskripsi servis (opsional)"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-bgray-200 dark:border-darkblack-400 px-6 py-4 flex justify-between">
                        <div>
                            <button type="button" id="deleteBtn" onclick="deleteEvent()" class="hidden rounded-lg bg-error-300 px-4 py-2 text-sm font-medium text-white hover:bg-error-400 transition-colors">
                                <i class="fa fa-trash mr-2"></i>Hapus
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal()" class="rounded-lg border border-bgray-300 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                                Batal
                            </button>
                            <button type="submit" id="submitBtn" class="rounded-lg bg-success-300 px-4 py-2 text-sm font-medium text-white hover:bg-success-400 transition-colors">
                                <span id="submitText">Simpan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Event Modal -->
    <div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 pb-20 pt-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeViewModal()"></div>

            <div class="relative inline-block w-full max-w-md transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all dark:bg-darkblack-600 sm:my-8 sm:align-middle">
                <div class="border-b border-bgray-200 dark:border-darkblack-400 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 id="viewModalTitle" class="text-lg font-semibold text-bgray-900 dark:text-white">Detail Event</h3>
                        <button type="button" onclick="closeViewModal()" class="text-bgray-400 hover:text-bgray-600 dark:hover:text-white">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div id="viewContent" class="space-y-3">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>

                <div class="border-t border-bgray-200 dark:border-darkblack-400 px-6 py-4 flex justify-end gap-2">
                    <a id="viewDetailLink" href="#" class="rounded-lg border border-bgray-300 px-4 py-2 text-sm font-medium text-bgray-700 hover:bg-bgray-50 dark:border-darkblack-400 dark:text-bgray-300 dark:hover:bg-darkblack-500 transition-colors">
                        <i class="fa fa-eye mr-2"></i>Lihat Detail
                    </a>
                    <button type="button" id="editEventBtn" onclick="editCurrentEvent()" class="rounded-lg bg-warning-300 px-4 py-2 text-sm font-medium text-white hover:bg-warning-400 transition-colors">
                        <i class="fa fa-edit mr-2"></i>Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/calender.js') }}"></script>
    <script>
        let calendar;
        let currentEvent = null;
        let modalMode = 'add'; // 'add' or 'edit'
        let currentEventType = 'pajak';

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'multiMonthYear,dayGridMonth,timeGridWeek,listMonth'
                },
                buttonText: {
                    today: 'Hari Ini',
                    year: 'Tahun',
                    month: 'Bulan',
                    week: 'Minggu',
                    list: 'Daftar'
                },
                views: {
                    multiMonthYear: {
                        type: 'multiMonth',
                        duration: { years: 1 },
                        buttonText: 'Tahun',
                        multiMonthMaxColumns: 3
                    }
                },
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                events: {
                    url: '{{ route("api.calendar.events") }}',
                    method: 'GET',
                    failure: function() {
                        alert('Gagal memuat data kalender');
                    }
                },
                eventClick: function(info) {
                    showViewModal(info.event);
                },
                eventDrop: function(info) {
                    moveEvent(info);
                },
                select: function(info) {
                    openAddModal('pajak', info.startStr);
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    const props = info.event.extendedProps;
                    let tooltip = props.kendaraan || '';
                    if (props.jenisLabel) {
                        tooltip += ' - ' + props.jenisLabel;
                    }
                    if (props.statusLabel) {
                        tooltip += ' (' + props.statusLabel + ')';
                    }
                    info.el.title = tooltip;
                }
            });

            calendar.render();
        });

        function openAddModal(type, date = null) {
            modalMode = 'add';
            currentEvent = null;
            currentEventType = type;

            // Reset form
            document.getElementById('eventForm').reset();

            // Set event type
            document.querySelector(`input[name="event_type"][value="${type}"]`).checked = true;
            switchEventType(type);

            // Set date if provided
            if (date) {
                if (type === 'pajak') {
                    document.getElementById('tanggal_jatuh_tempo').value = date;
                } else {
                    document.getElementById('tanggal_servis').value = date;
                }
            }

            // Show type selector for add mode
            document.getElementById('typeSelector').classList.remove('hidden');

            // Hide status fields for add mode
            document.getElementById('pajakStatusField').classList.add('hidden');
            document.getElementById('servisStatusField').classList.add('hidden');

            // Hide delete button
            document.getElementById('deleteBtn').classList.add('hidden');

            // Update modal title
            document.getElementById('modalTitle').textContent = type === 'pajak' ? 'Tambah Pajak' : 'Tambah Servis';
            document.getElementById('submitText').textContent = 'Simpan';

            // Show modal
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function openEditModal(event) {
            modalMode = 'edit';
            currentEvent = event;
            const props = event.extendedProps;
            currentEventType = props.type;

            // Reset form
            document.getElementById('eventForm').reset();

            // Hide type selector for edit mode
            document.getElementById('typeSelector').classList.add('hidden');

            // Show delete button
            document.getElementById('deleteBtn').classList.remove('hidden');

            // Switch to correct type
            switchEventType(props.type);

            // Populate form based on type
            document.getElementById('kendaraan_id').value = props.kendaraan_id;

            if (props.type === 'pajak') {
                document.getElementById('pajak_jenis').value = props.jenis;
                document.getElementById('tanggal_jatuh_tempo').value = event.startStr;
                document.getElementById('pajak_nominal').value = props.nominal || '';
                document.getElementById('pajak_status').value = props.status;
                document.getElementById('pajak_catatan').value = props.catatan || '';

                // Show status field for edit mode
                document.getElementById('pajakStatusField').classList.remove('hidden');
            } else if (props.type === 'servis') {
                document.getElementById('servis_jenis').value = props.jenis;
                document.getElementById('tanggal_servis').value = event.startStr;
                document.getElementById('servis_bengkel').value = props.bengkel || '';
                document.getElementById('servis_biaya').value = props.biaya || '';
                document.getElementById('servis_status').value = props.status;
                document.getElementById('servis_deskripsi').value = props.deskripsi || '';

                // Show status field for edit mode
                document.getElementById('servisStatusField').classList.remove('hidden');
            }

            // Update modal title
            document.getElementById('modalTitle').textContent = props.type === 'pajak' ? 'Edit Pajak' : 'Edit Servis';
            document.getElementById('submitText').textContent = 'Perbarui';

            // Close view modal and show edit modal
            closeViewModal();
            document.getElementById('eventModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('eventModal').classList.add('hidden');
            currentEvent = null;
        }

        function switchEventType(type) {
            currentEventType = type;
            if (type === 'pajak') {
                document.getElementById('pajakFields').classList.remove('hidden');
                document.getElementById('servisFields').classList.add('hidden');
            } else {
                document.getElementById('pajakFields').classList.add('hidden');
                document.getElementById('servisFields').classList.remove('hidden');
            }
        }

        function submitForm(e) {
            e.preventDefault();

            const formData = {
                kendaraan_id: document.getElementById('kendaraan_id').value
            };

            let url, method;

            if (currentEventType === 'pajak') {
                formData.jenis = document.getElementById('pajak_jenis').value;
                formData.tanggal_jatuh_tempo = document.getElementById('tanggal_jatuh_tempo').value;
                formData.nominal = document.getElementById('pajak_nominal').value || null;
                formData.catatan = document.getElementById('pajak_catatan').value || null;

                if (modalMode === 'edit') {
                    formData.status = document.getElementById('pajak_status').value;
                    url = `/api/calendar/pajak/${currentEvent.extendedProps.modelId}`;
                    method = 'PUT';
                } else {
                    url = '/api/calendar/pajak';
                    method = 'POST';
                }
            } else {
                formData.jenis = document.getElementById('servis_jenis').value;
                formData.tanggal_servis = document.getElementById('tanggal_servis').value;
                formData.bengkel = document.getElementById('servis_bengkel').value || null;
                formData.biaya = document.getElementById('servis_biaya').value || null;
                formData.deskripsi = document.getElementById('servis_deskripsi').value || null;

                if (modalMode === 'edit') {
                    formData.status = document.getElementById('servis_status').value;
                    url = `/api/calendar/servis/${currentEvent.extendedProps.modelId}`;
                    method = 'PUT';
                } else {
                    url = '/api/calendar/servis';
                    method = 'POST';
                }
            }

            // Submit
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    closeModal();
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        function deleteEvent() {
            if (!confirm('Yakin ingin menghapus event ini?')) return;

            const props = currentEvent.extendedProps;
            const url = props.type === 'pajak'
                ? `/api/calendar/pajak/${props.modelId}`
                : `/api/calendar/servis/${props.modelId}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    closeModal();
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Gagal menghapus', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        function moveEvent(info) {
            const props = info.event.extendedProps;

            // Don't allow moving next_servis events
            if (props.type === 'next_servis') {
                info.revert();
                showNotification('Event pengingat servis berikutnya tidak dapat dipindahkan', 'error');
                return;
            }

            const url = `/api/calendar/${props.type}/${props.modelId}/move`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    start: info.event.startStr
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    info.revert();
                    showNotification(data.message || 'Gagal memperbarui jadwal', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                info.revert();
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        function showViewModal(event) {
            currentEvent = event;
            const props = event.extendedProps;

            let title = props.type === 'pajak' ? 'Detail Pajak' :
                       (props.type === 'next_servis' ? 'Pengingat Servis Berikutnya' : 'Detail Servis');
            document.getElementById('viewModalTitle').textContent = title;

            let content = `
                <div class="flex items-center gap-2 mb-3">
                    <span class="h-4 w-4 rounded-full" style="background-color: ${event.backgroundColor};"></span>
                    <span class="font-semibold text-bgray-900 dark:text-white">${event.title}</span>
                </div>
                <div class="space-y-2 text-sm">
                    <p><span class="text-bgray-500 dark:text-bgray-400">Kendaraan:</span> <span class="text-bgray-900 dark:text-white">${props.kendaraan}</span></p>
                    <p><span class="text-bgray-500 dark:text-bgray-400">Jenis:</span> <span class="text-bgray-900 dark:text-white">${props.jenisLabel}</span></p>
                    <p><span class="text-bgray-500 dark:text-bgray-400">Tanggal:</span> <span class="text-bgray-900 dark:text-white">${formatDate(event.startStr)}</span></p>
            `;

            if (props.type === 'pajak') {
                content += `
                    <p><span class="text-bgray-500 dark:text-bgray-400">Status:</span> <span class="text-bgray-900 dark:text-white">${props.statusLabel}</span></p>
                    <p><span class="text-bgray-500 dark:text-bgray-400">Nominal:</span> <span class="text-bgray-900 dark:text-white">${props.nominalFormatted}</span></p>
                `;
                if (props.daysUntilDue !== undefined) {
                    let dueText = props.daysUntilDue === 0 ? 'Hari ini' :
                                 props.daysUntilDue > 0 ? `${props.daysUntilDue} hari lagi` :
                                 `Terlambat ${Math.abs(props.daysUntilDue)} hari`;
                    content += `<p><span class="text-bgray-500 dark:text-bgray-400">Jatuh Tempo:</span> <span class="${props.daysUntilDue < 0 ? 'text-error-300' : 'text-bgray-900 dark:text-white'}">${dueText}</span></p>`;
                }
            } else if (props.type === 'servis') {
                content += `
                    <p><span class="text-bgray-500 dark:text-bgray-400">Status:</span> <span class="text-bgray-900 dark:text-white">${props.statusLabel}</span></p>
                `;
                if (props.bengkel) {
                    content += `<p><span class="text-bgray-500 dark:text-bgray-400">Bengkel:</span> <span class="text-bgray-900 dark:text-white">${props.bengkel}</span></p>`;
                }
                if (props.biayaFormatted) {
                    content += `<p><span class="text-bgray-500 dark:text-bgray-400">Biaya:</span> <span class="text-bgray-900 dark:text-white">${props.biayaFormatted}</span></p>`;
                }
            } else if (props.type === 'next_servis') {
                if (props.daysUntilDue !== undefined) {
                    let dueText = props.daysUntilDue === 0 ? 'Hari ini' :
                                 props.daysUntilDue > 0 ? `${props.daysUntilDue} hari lagi` :
                                 `Lewat ${Math.abs(props.daysUntilDue)} hari`;
                    content += `<p><span class="text-bgray-500 dark:text-bgray-400">Jadwal:</span> <span class="text-bgray-900 dark:text-white">${dueText}</span></p>`;
                }
            }

            content += '</div>';
            document.getElementById('viewContent').innerHTML = content;

            // Set detail link
            document.getElementById('viewDetailLink').href = props.viewUrl;

            // Show/hide edit button based on type
            if (props.type === 'next_servis') {
                document.getElementById('editEventBtn').classList.add('hidden');
            } else {
                document.getElementById('editEventBtn').classList.remove('hidden');
            }

            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function editCurrentEvent() {
            if (currentEvent) {
                openEditModal(currentEvent);
            }
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function showNotification(message, type) {
            // Simple notification - you can enhance this
            const bgColor = type === 'success' ? 'bg-success-50 border-success-300 text-success-700' : 'bg-error-50 border-error-300 text-error-700';
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[100] px-4 py-3 rounded-lg border ${bgColor} shadow-lg transition-opacity duration-300`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
    @endpush
</x-app-layout>
