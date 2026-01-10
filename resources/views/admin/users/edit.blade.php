<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Edit User</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('admin.users.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">Daftar User</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <a type="button" href="{{ route('admin.users.show', $user) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">{{ $user->name }}</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Edit User</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="self-stretch mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm: rounded-lg">
                <div class="p-6">
                    <x-admin.user.form-fields :user="$user" />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>