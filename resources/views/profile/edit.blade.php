<x-app-layout title="Profil Saya">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-bgray-900 dark:text-white">Profil Saya</h2>
                <p class="text-sm text-bgray-600 dark:text-bgray-400 mt-1">Kelola informasi akun Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Profile Information -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-4">Informasi Profil</h3>
            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-6">Perbarui informasi profil dan alamat email akun Anda.</p>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-3">Foto Profil</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div id="avatar-preview" class="h-24 w-24 overflow-hidden rounded-xl border-2 border-bgray-200 dark:border-darkblack-400 bg-bgray-100 dark:bg-darkblack-500">
                                <img id="preview-img" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            </div>
                            <label for="avatar" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-accent-300 text-white hover:bg-accent-400 transition-colors">
                                <i class="fa fa-camera text-xs"></i>
                            </label>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewAvatar(this)">
                            <p class="text-sm text-bgray-600 dark:text-bgray-400">Klik ikon kamera untuk mengganti foto</p>
                            <p class="text-xs text-bgray-500 dark:text-bgray-400 mt-1">Format: JPG, PNG, WebP. Maks 2MB</p>
                            @if($user->avatar_path)
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-error-300 cursor-pointer">
                                <input type="checkbox" name="remove_avatar" value="1" class="rounded border-bgray-300 text-error-300 focus:ring-error-300">
                                <span>Hapus foto profil</span>
                            </label>
                            @endif
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Accent Color Picker -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-3">Warna Aksen</label>
                    <p class="text-xs text-bgray-500 dark:text-bgray-400 mb-4">Pilih warna aksen untuk tampilan aplikasi Anda</p>

                    <!-- Preset Colors -->
                    <div class="flex flex-wrap gap-3 mb-4">
                        @foreach($accentPresets as $hex => $preset)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="accent_color" value="{{ $hex }}"
                                    class="sr-only peer"
                                    {{ (old('accent_color', $user->accent_color) ?? '#22C55E') === $hex ? 'checked' : '' }}
                                    onchange="updateAccentPreview('{{ $hex }}')">
                                <div class="w-10 h-10 rounded-full border-2 border-transparent peer-checked:border-bgray-900 dark:peer-checked:border-white peer-checked:ring-2 peer-checked:ring-offset-2 ring-offset-white dark:ring-offset-darkblack-600 transition-all"
                                    style="background-color: {{ $hex }};"
                                    title="{{ $preset['name'] }}">
                                </div>
                                <span class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-[10px] text-bgray-500 dark:text-bgray-400 whitespace-nowrap">{{ $preset['name'] }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Custom Color Picker -->
                    <div class="mt-8 flex items-center gap-3">
                        <label class="text-sm text-bgray-600 dark:text-bgray-400">Atau pilih warna kustom:</label>
                        <div class="relative">
                            <input type="color" id="custom-color-picker"
                                value="{{ old('accent_color', $user->accent_color) ?? '#22C55E' }}"
                                class="w-10 h-10 rounded-lg border border-bgray-200 dark:border-darkblack-400 cursor-pointer"
                                onchange="selectCustomColor(this.value)">
                        </div>
                        <span id="custom-color-hex" class="text-xs text-bgray-500 dark:text-bgray-400 font-mono">{{ old('accent_color', $user->accent_color) ?? '#22C55E' }}</span>
                    </div>

                    @error('accent_color')
                        <p class="mt-2 text-sm text-error-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('name')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('email')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-darkblack-600">
            <h3 class="text-lg font-semibold text-bgray-900 dark:text-white mb-4">Ubah Password</h3>
            <p class="text-sm text-bgray-600 dark:text-bgray-400 mb-6">Pastikan akun Anda menggunakan password yang panjang dan acak untuk keamanan.</p>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('current_password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                        @error('password')
                            <p class="mt-1 text-sm text-error-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-bgray-700 dark:text-bgray-300 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border border-bgray-200 px-4 py-3 text-bgray-900 focus:border-accent-300 focus:ring-0 dark:border-darkblack-400 dark:bg-darkblack-500 dark:text-white">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="rounded-lg bg-accent-300 px-6 py-3 text-sm font-medium text-white hover:bg-accent-400 transition-colors">
                            Ubah Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Accent color functions
    function updateAccentPreview(hex) {
        // Update CSS variables in real-time
        const palette = generatePalette(hex);
        document.documentElement.style.setProperty('--accent-50', palette['50']);
        document.documentElement.style.setProperty('--accent-100', palette['100']);
        document.documentElement.style.setProperty('--accent-200', palette['200']);
        document.documentElement.style.setProperty('--accent-300', palette['300']);
        document.documentElement.style.setProperty('--accent-400', palette['400']);

        // Update custom color picker
        document.getElementById('custom-color-picker').value = hex;
        document.getElementById('custom-color-hex').textContent = hex.toUpperCase();
    }

    function selectCustomColor(hex) {
        // Uncheck all preset radios
        document.querySelectorAll('input[name="accent_color"]').forEach(radio => {
            radio.checked = false;
        });

        // Create hidden input for custom color if not exists
        let customInput = document.getElementById('custom-accent-input');
        if (!customInput) {
            customInput = document.createElement('input');
            customInput.type = 'hidden';
            customInput.name = 'accent_color';
            customInput.id = 'custom-accent-input';
            document.querySelector('form').appendChild(customInput);
        }
        customInput.value = hex;

        // Update preview
        updateAccentPreview(hex);
    }

    function generatePalette(hex) {
        // Preset palettes
        const presets = @json($accentPresets);
        if (presets[hex]) {
            return presets[hex];
        }

        // Generate palette from single color
        const rgb = hexToRgb(hex);
        return {
            '50': adjustBrightness(rgb.r, rgb.g, rgb.b, 0.9),
            '100': adjustBrightness(rgb.r, rgb.g, rgb.b, 0.8),
            '200': adjustBrightness(rgb.r, rgb.g, rgb.b, 0.4),
            '300': hex,
            '400': adjustBrightness(rgb.r, rgb.g, rgb.b, -0.15)
        };
    }

    function hexToRgb(hex) {
        hex = hex.replace('#', '');
        return {
            r: parseInt(hex.substring(0, 2), 16),
            g: parseInt(hex.substring(2, 4), 16),
            b: parseInt(hex.substring(4, 6), 16)
        };
    }

    function adjustBrightness(r, g, b, percent) {
        if (percent > 0) {
            r = r + (255 - r) * percent;
            g = g + (255 - g) * percent;
            b = b + (255 - b) * percent;
        } else {
            const factor = 1 + percent;
            r = r * factor;
            g = g * factor;
            b = b * factor;
        }
        return '#' + [r, g, b].map(x => {
            const hex = Math.min(255, Math.max(0, Math.round(x))).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        }).join('').toUpperCase();
    }
    </script>
</x-app-layout>
