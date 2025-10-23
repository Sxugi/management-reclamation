<!-- Plot List -->
<div class="mt-6 bg-white rounded-2xl border border-gainsboro shadow-sm">
    <div class="w-full relative overflow-hidden flex flex-row items-center justify-between py-4 px-6">
        <div class="flex flex-col items-start justify-start">
            <div class="self-stretch relative leading-7 font-semibold text-lg text-darkslategray font-outfit">Daftar Plot</div>
        </div>
    </div>

    <div class="overflow-x-auto">
    <table class="w-full text-xs text-darkslategray font-outfit table-auto border-collapse">
            <thead class="border-gainsboro border-t border-b">
                <tr>
                    <th class="h-6 py-3 px-6 text-center text-sm leading-5 font-bold border-gainsboro border-r">Nama Plot</th>
                    <th class="h-6 py-3 px-6 text-center text-sm leading-5 font-bold border-gainsboro border-r">Luas Area (Ha)</th>
                    <th class="h-6 py-3 px-6 text-center text-sm leading-5 font-bold border-gainsboro border-l">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plot ?? [] as $plot)
                    <tr>
                        <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r">{{ $plot->nama_plot }}</td>
                        <td class="py-3 px-6 text-sm text-center text-gray leading-5 border-gainsboro border-t border-r">{{ $plot->luas_area }}</td>
                        <td class="py-3 px-6 border-gainsboro border-t border-l">
                            <div class="flex flex-row items-center justify-center gap-3">
                                <a href="{{ route('plot.show', $plot->plot_id) }}">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" fill="white"/>
                                        <path d="M0.200195 9.99997C1.12139 8.19299 2.52424 6.67588 4.25372 5.61631C5.98319 4.55674 7.97195 3.99597 10.0002 3.99597C12.0284 3.99597 14.0172 4.55674 15.7467 5.61631C17.4762 6.67588 18.879 8.19299 19.8002 9.99997C18.879 11.807 17.4762 13.3241 15.7467 14.3836C14.0172 15.4432 12.0284 16.004 10.0002 16.004C7.97195 16.004 5.98319 15.4432 4.25372 14.3836C2.52424 13.3241 1.12139 11.807 0.200195 9.99997ZM10.0002 14C11.0611 14 12.0785 13.5785 12.8286 12.8284C13.5788 12.0783 14.0002 11.0608 14.0002 9.99997C14.0002 8.93911 13.5788 7.92169 12.8286 7.17155C12.0785 6.4214 11.0611 5.99997 10.0002 5.99997C8.93933 5.99997 7.92191 6.4214 7.17177 7.17155C6.42162 7.92169 6.0002 8.93911 6.0002 9.99997C6.0002 11.0608 6.42162 12.0783 7.17177 12.8284C7.92191 13.5785 8.93933 14 10.0002 14ZM10.0002 12C9.46976 12 8.96106 11.7893 8.58598 11.4142C8.21091 11.0391 8.0002 10.5304 8.0002 9.99997C8.0002 9.46954 8.21091 8.96083 8.58598 8.58576C8.96106 8.21069 9.46976 7.99997 10.0002 7.99997C10.5306 7.99997 11.0393 8.21069 11.4144 8.58576C11.7895 8.96083 12.0002 9.46954 12.0002 9.99997C12.0002 10.5304 11.7895 11.0391 11.4144 11.4142C11.0393 11.7893 10.5306 12 10.0002 12Z" fill="#1D2939"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('plot.edit', $plot->plot_id) }}">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="#1D2939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="#1D2939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                
                                <x-main.modal name="confirm-plot-deletion-{{ $plot->plot_id }}" focusable>
                                    <form method="POST" action="{{ route('plot.destroy', $plot->plot_id) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <h2 class="text-lg font-medium text-gray-900">
                                            {{ __('Are you sure you want to delete this plot?') }}
                                        </h2>
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ __('Once deleted, all data related to this plot will be permanently lost. This action cannot be undone.') }}
                                        </p>
                                        <div class="mt-6 flex justify-end font-outfit">
                                            <x-main.secondary-button @click="$dispatch('close')">
                                                {{ __('Cancel') }}
                                            </x-main.secondary-button>
                                            <x-main.danger-button type="submit" class="ml-3">
                                                {{ __('Delete') }}
                                            </x-main.danger-button>
                                        </div>
                                    </form>
                                </x-main.modal>
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-plot-deletion-{{ $plot->plot_id }}')" class="border-none bg-transparent cursor-pointer p-0">
                                    <svg width="20" height="20" viewBox="0 0 25 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.54464 1.50254L7.14286 2.3125H1.78571C0.797991 2.3125 0 3.12246 0 4.125C0 5.12754 0.797991 5.9375 1.78571 5.9375H23.2143C24.202 5.9375 25 5.12754 25 4.125C25 3.12246 24.202 2.3125 23.2143 2.3125H17.8571L17.4554 1.50254C17.154 0.885156 16.5346 0.5 15.8594 0.5H9.14062C8.4654 0.5 7.84598 0.885156 7.54464 1.50254ZM23.2143 7.75H1.78571L2.96875 26.9512C3.05804 28.3842 4.22991 29.5 5.64174 29.5H19.3583C20.7701 29.5 21.942 28.3842 22.0312 26.9512L23.2143 7.75Z" fill="#F24822"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-none">
                        <td colspan="3" class="py-6 px-6 text-center text-gray-400">No plot available yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>