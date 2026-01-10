<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-between items-center justify-between gap-4 sm:gap-0 w-full">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">
                {{ __('Profile') }}
            </h2>
            <a href="javascript:history.back()" class="text-sm rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                <span class="relative leading-5 font-medium font-outfit">Back</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.33312 8.33335L2.74395 8.92251L2.15479 8.33335L2.74395 7.74418L3.33312 8.33335ZM17.4998 15C17.4998 15.221 17.412 15.433 17.2557 15.5893C17.0994 15.7456 16.8875 15.8333 16.6665 15.8333C16.4454 15.8333 16.2335 15.7456 16.0772 15.5893C15.9209 15.433 15.8331 15.221 15.8331 15H17.4998ZM6.91062 13.0892L2.74395 8.92251L3.92228 7.74418L8.08895 11.9108L6.91062 13.0892ZM2.74395 7.74418L6.91062 3.57751L8.08895 4.75585L3.92228 8.92251L2.74395 7.74418ZM3.33312 7.50001H11.6665V9.16668H3.33312V7.50001ZM17.4998 13.3333V15H15.8331V13.3333H17.4998ZM11.6665 7.50001C13.2135 7.50001 14.6973 8.1146 15.7912 9.20856C16.8852 10.3025 17.4998 11.7863 17.4998 13.3333H15.8331C15.8331 12.2283 15.3941 11.1685 14.6127 10.3871C13.8313 9.60567 12.7715 9.16668 11.6665 9.16668V7.50001Z" fill="white"/>
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto">
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