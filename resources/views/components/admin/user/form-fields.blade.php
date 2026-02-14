@props(['user' => null])

<form method="POST" 
      action="{{ $user ? route('admin.users.update', $user) :route('admin.users.store') }}" 
      enctype="multipart/form-data">
    @csrf
    @if($user)
        @method('PUT')
    @endif

    <div class="w-full relative rounded-2xl bg-white border-gainsboro border-solid border-[1px] box-border flex flex-col items-center justify-start text-left text-base text-gray font-outfit">
        
        <!-- Header -->
        <div class="self-stretch border-gainsboro border-solid border-b-[1px] border-[0px] flex flex-row items-start justify-start py-5 px-6">
            <div class="flex flex-col items-start justify-start">
                <div class="relative leading-6 font-semibold text-darkslategray">Informasi User</div>
                <div class="text-sm text-slategray mt-1">
                    {{ $user ? 'Update informasi user yang sudah ada' :'Lengkapi form dibawah untuk menambahkan user baru' }}
                </div>
            </div>
        </div>
        
        <!-- Form Fields -->
        <div class="self-stretch flex flex-col items-start justify-start p-6 gap-6 text-sm text-darkslategray-200">

            <!-- Avatar Upload -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Foto Profile
                </x-main.input-label>
                
                <div class="flex items-center gap-4">
                    <!-- Preview Avatar -->
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-2xl overflow-hidden" id="avatar-preview">
                        @if($user?->avatar)
                            <img src="{{ asset('storage/' .$user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>

                    <!-- Upload Button -->
                    <div class="flex-1">
                        <input 
                            type="file" 
                            name="avatar" 
                            id="avatar"
                            accept="image/jpeg,image/png,image/jpg"
                            class="hidden"
                            onchange="previewAvatar(event)"
                        />
                        <label for="avatar" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-darkslategray hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pilih Foto
                        </label>
                        <p class="text-xs text-slategray mt-1">JPG, JPEG atau PNG.  Maksimal 2MB</p>
                    </div>
                </div>
                <x-main.input-error :messages="$errors->get('avatar')" data-turbo-temporary />
            </div>

            <div class="w-full border-t border-gainsboro"></div>

            <!-- Username -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Username
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="text" 
                    name="username"
                    value="{{ old('username', $user?->username) }}"
                    placeholder="Masukkan username (contoh:johndoe)"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Username harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <p class="text-xs text-slategray-100">Username hanya boleh berisi huruf, angka, strip (-) dan underscore (_)</p>
                <x-main.input-error :messages="$errors->get('username')" data-turbo-temporary />
            </div>

            <!-- Full Name -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Nama Lengkap
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="text" 
                    name="name"
                    value="{{ old('name', $user?->name) }}"
                    placeholder="Masukkan nama lengkap"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Nama lengkap harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('name')" data-turbo-temporary />
            </div>

            <!-- Email -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Email
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <x-main.text-input 
                    type="email" 
                    name="email"
                    value="{{ old('email', $user?->email) }}"
                    placeholder="Masukkan alamat email"
                    class="flex-1 leading-5 bg-transparent text-sm"
                    required
                    oninvalid="this.setCustomValidity('Email harus diisi')"
                    oninput="this.setCustomValidity('')"
                />
                <x-main.input-error :messages="$errors->get('email')" data-turbo-temporary />
            </div>

            <!-- Phone -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Nomor Telepon
                </x-main.input-label>
                <x-main.text-input 
                    type="tel" 
                    name="phone"
                    value="{{ old('phone', $user?->phone) }}"
                    placeholder="Masukkan nomor telepon (contoh:08123456789)"
                    class="flex-1 leading-5 bg-transparent text-sm"
                />
                <x-main.input-error :messages="$errors->get('phone')" data-turbo-temporary />
            </div>

            <div class="w-full border-t border-gainsboro"></div>

            <!-- Password -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Password
                    <span class="text-red-500">{{ $user ? '' :  '*' }}</span>
                </x-main.input-label>
                <div class="relative w-full">
                    <input 
                        type="password" 
                        name="password"
                        id="password"
                        placeholder="{{ $user ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password (minimal 8 karakter)' }}"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md pl-3 pr-10 py-2.5 box-border font-outfit leading-5 bg-white"
                        {{ $user ? '' : 'required' }}
                        oninvalid="this.setCustomValidity('Password harus diisi')"
                        oninput="this.setCustomValidity('')"
                    />
                    <a type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slategray">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                </div>
                <x-main.input-error :messages="$errors->get('password')" data-turbo-temporary />
            </div>

            <!-- Password Confirmation -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Konfirmasi Password
                    <span class="text-red-500">{{ $user ? '' :  '*' }}</span>
                </x-main.input-label>
                <div class="relative w-full">
                    <input 
                        type="password" 
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Masukkan ulang password"
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md pl-3 pr-10 py-2.5 box-border font-outfit leading-5 bg-white"
                        {{ $user ? '' : 'required' }}
                    />
                    <a type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slategray">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                </div>
                <x-main.input-error :messages="$errors->get('password_confirmation')" data-turbo-temporary />
            </div>

            <div class="w-full border-t border-gainsboro"></div>

            <!-- Role -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Role
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="role"
                    required 
                    oninvalid="this.setCustomValidity('Role harus dipilih')" 
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit leading-5 bg-transparent"
                >
                    <option value="">Pilih Role</option>
                    <option value="user" {{ old('role', $user?->role) == 'user' ? 'selected' :'' }}>
                        👤 User - Pengguna Biasa
                    </option>
                    <option value="admin" {{ old('role', $user?->role) == 'admin' ? 'selected' :'' }}>
                        👑 Admin - Administrator Sistem
                    </option>
                </select>
                <p class="text-xs text-slategray">Admin memiliki akses penuh ke semua fitur sistem</p>
                <x-main.input-error :messages="$errors->get('role')" data-turbo-temporary />
            </div>

            <!-- Status -->
            <div class="self-stretch flex flex-col items-start justify-start gap-1.5">
                <x-main.input-label class="relative leading-5 font-medium">
                    Status
                    <span class="text-red-500">*</span>
                </x-main.input-label>
                <select 
                    name="status"
                    required 
                    oninvalid="this.setCustomValidity('Status harus dipilih')" 
                    oninput="this.setCustomValidity('')"
                    class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit leading-5 bg-transparent"
                >
                    <option value="">Pilih Status</option>
                    <option value="active" {{ old('status', $user?->status ??  'active') == 'active' ? 'selected' :'' }}>
                        ✅ Active - User dapat login
                    </option>
                    <option value="inactive" {{ old('status', $user?->status) == 'inactive' ? 'selected' :'' }}>
                        ⚠️ Inactive - User tidak dapat login
                    </option>
                    <option value="suspended" {{ old('status', $user?->status) == 'suspended' ? 'selected' :'' }}>
                        🚫 Suspended - Akun dibekukan
                    </option>
                </select>
                <x-main.input-error :messages="$errors->get('status')" data-turbo-temporary />
            </div>

            <!-- Action Buttons -->
            <div class="self-stretch flex flex-row items-center justify-end gap-3 mt-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-semibold hover:bg-red-600 transition-colors no-underline">
                    Cancel
                </a>
                <x-main.primary-button type="submit" class="py-3 px-4 gap-2">
                    {{ $user ? 'Update' :'Save' }}
                </x-main.primary-button>
            </div>
        </div>
    </div>
</form>

<script>
    // Preview Avatar
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
            }
            reader.readAsDataURL(file);
        }
    }

    // Toggle Password Visibility
    function togglePassword(id) {
        const input = document.getElementById(id);
        const button = input.parentElement.querySelector('a');
        
        if (input.type === 'password') {
            input.type = 'text';
            // Change icon to eye-off
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            `;
        } else {
            input.type = 'password';
            // Change icon to eye
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            `;
        }
    }
</script>