<!-- Plot List -->
<div class="mt-6">
    <!-- Header Section -->
    <div class="w-full relative rounded-t-2xl bg-white border-gainsboro border-solid border-[1px] border-b-0 box-border overflow-hidden flex flex-row items-center justify-between py-4 px-6">
        <div class="flex flex-col items-start justify-start">
            <div class="self-stretch relative leading-7 font-semibold text-lg text-darkslategray font-outfit">Daftar Plot</div>
        </div>
    </div>

    <!-- Table Structure -->
    <div class="overflow-x-auto rounded-b-2xl border-gainsboro border-solid border-[1px]">
        <table class="w-full text-xs text-darkslategray font-outfit table-auto">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th class="h-6 py-3 px-6 text-center leading-[18px] font-bold">Nama Plot</th>
                    <th class="h-6 py-3 px-6 text-center leading-[18px] font-bold">Luas Area (Ha)</th>
                    <th class="h-6 py-3 px-6 text-center leading-[18px] font-bold">Actions</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                @forelse($plot ?? [] as $plot)
                    <tr>
                        <td class="py-3 px-6 text-sm text-gray leading-5 font-medium">{{ $plot->nama_plot }}</td>
                        <td class="py-3 px-6 text-sm text-gray leading-5">{{ $plot->area }}</td>
                        <td class="py-3 px-6">
                            <div class="flex flex-row items-center justify-center gap-3">
                                <!-- View Icon -->
                                <a href="{{ route('plot.show', $plot->plot_id) }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" fill="white"/>
                                        <path d="M0.240234 12C1.34566 9.83165 3.02909 8.01112 5.10446 6.73964C7.17983 5.46815 9.56634 4.79523 12.0002 4.79523C14.4341 4.79523 16.8206 5.46815 18.896 6.73964C20.9714 8.01112 22.6548 9.83165 23.7602 12C22.6548 14.1684 20.9714 15.9889 18.896 17.2604C16.8206 18.5319 14.4341 19.2048 12.0002 19.2048C9.56634 19.2048 7.17983 18.5319 5.10446 17.2604C3.02909 15.9889 1.34566 14.1684 0.240234 12ZM12.0002 16.8C13.2733 16.8 14.4942 16.2943 15.3943 15.3941C16.2945 14.494 16.8002 13.2731 16.8002 12C16.8002 10.727 16.2945 9.50609 15.3943 8.60592C14.4942 7.70574 13.2733 7.20003 12.0002 7.20003C10.7272 7.20003 9.5063 7.70574 8.60612 8.60592C7.70595 9.50609 7.20023 10.727 7.20023 12C7.20023 13.2731 7.70595 14.494 8.60612 15.3941C9.5063 16.2943 10.7272 16.8 12.0002 16.8ZM12.0002 14.4C11.3637 14.4 10.7533 14.1472 10.3032 13.6971C9.85309 13.247 9.60023 12.6365 9.60023 12C9.60023 11.3635 9.85309 10.7531 10.3032 10.303C10.7533 9.85289 11.3637 9.60003 12.0002 9.60003C12.6368 9.60003 13.2472 9.85289 13.6973 10.303C14.1474 10.7531 14.4002 11.3635 14.4002 12C14.4002 12.6365 14.1474 13.247 13.6973 13.6971C13.2472 14.1472 12.6368 14.4 12.0002 14.4Z" fill="#1D2939"/>
                                    </svg>
                                </a>
                                
                                <!-- Edit Icon -->
                                <a href="{{ route('plot.edit', $plot->plot_id) }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 7H6C5.46957 7 4.96086 7.21071 4.58579 7.58579C4.21071 7.96086 4 8.46957 4 9V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H15C15.5304 20 16.0391 19.7893 16.4142 19.4142C16.7893 19.0391 17 18.5304 17 18V17" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16 4.99998L19 7.99998M20.385 6.58499C20.7788 6.19114 21.0001 5.65697 21.0001 5.09998C21.0001 4.543 20.7788 4.00883 20.385 3.61498C19.9912 3.22114 19.457 2.99988 18.9 2.99988C18.343 2.99988 17.8088 3.22114 17.415 3.61498L9 12V15H12L20.385 6.58499Z" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                                
                                <!-- Delete Icon -->
                                <form action="{{ route('plot.destroy', $plot->plot_id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this plot?')" class="border-none bg-transparent cursor-pointer">
                                        <svg width="20" height="20" viewBox="0 0 25 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.54464 1.50254L7.14286 2.3125H1.78571C0.797991 2.3125 0 3.12246 0 4.125C0 5.12754 0.797991 5.9375 1.78571 5.9375H23.2143C24.202 5.9375 25 5.12754 25 4.125C25 3.12246 24.202 2.3125 23.2143 2.3125H17.8571L17.4554 1.50254C17.154 0.885156 16.5346 0.5 15.8594 0.5H9.14062C8.4654 0.5 7.84598 0.885156 7.54464 1.50254ZM23.2143 7.75H1.78571L2.96875 26.9512C3.05804 28.3842 4.22991 29.5 5.64174 29.5H19.3583C20.7701 29.5 21.942 28.3842 22.0312 26.9512L23.2143 7.75Z" fill="#F24822"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="border-whitesmoke border-solid border-b-[1px] bg-white">
                        <td colspan="3" class="py-6 px-6 text-center text-gray-400">No plot available yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>