<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight font-outfit">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg sm:rounded-lg overflow-hidden">
            <div class="p-4 sm:p-8">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg sm:rounded-lg overflow-hidden">
            <div class="p-4 sm:p-8">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg sm:rounded-lg overflow-hidden">
            <div class="p-4 sm:p-8">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>