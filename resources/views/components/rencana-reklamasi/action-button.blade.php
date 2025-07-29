@props([
    'lahan',
    'rencana_reklamasi',
    'tahun_aktif' => null,
])

@php
    $rencana_reklamasi = $rencana_reklamasi[$tahun_aktif] ?? null;
@endphp

<div class="flex flex-row items-center justify-between gap-2 py-4 text-white">
    @if (request()->routeIs('lahan.rencana-reklamasi.index'))
        <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center gap-2">
            @if ($rencana_reklamasi)
                <a href="{{ route('lahan.rencana-reklamasi.edit', [$lahan->lahan_id, $rencana_reklamasi->data_reklamasi_id]) }}" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                    <span class="relative leading-5 font-medium">Edit</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83301 5.83333H4.99967C4.55765 5.83333 4.13372 6.00892 3.82116 6.32148C3.5086 6.63404 3.33301 7.05797 3.33301 7.49999V15C3.33301 15.442 3.5086 15.8659 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.8659 14.1663 15.442 14.1663 15V14.1667" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            @else
                <a href="{{ route('lahan.rencana-reklamasi.create', $lahan->lahan_id) }}?tahun={{ $tahun_aktif }}" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                    <span class="relative leading-5 font-medium">Add</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                    </svg>
                </a>
            @endif
        </div>

        <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center gap-2">
            <a href="{{ route('lahan.rencana-reklamasi.pdf', $lahan->lahan_id) }}" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                <span class="relative leading-5 font-medium">Download</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.99967 12.9792C9.88856 12.9792 9.7844 12.9619 9.68717 12.9275C9.58995 12.8931 9.49967 12.8339 9.41634 12.75L6.41634 9.75C6.24967 9.58333 6.16967 9.38889 6.17634 9.16666C6.18301 8.94444 6.26301 8.75 6.41634 8.58333C6.58301 8.41666 6.78106 8.33 7.01051 8.32333C7.23995 8.31666 7.43773 8.39639 7.60384 8.5625L9.16634 10.125V4.16666C9.16634 3.93055 9.24634 3.73278 9.40634 3.57333C9.56634 3.41389 9.76412 3.33389 9.99967 3.33333C10.2352 3.33278 10.4333 3.41278 10.5938 3.57333C10.7544 3.73389 10.8341 3.93166 10.833 4.16666V10.125L12.3955 8.5625C12.5622 8.39583 12.7602 8.31583 12.9897 8.3225C13.2191 8.32916 13.4169 8.41611 13.583 8.58333C13.7358 8.75 13.8158 8.94444 13.823 9.16666C13.8302 9.38889 13.7502 9.58333 13.583 9.75L10.583 12.75C10.4997 12.8333 10.4094 12.8925 10.3122 12.9275C10.215 12.9625 10.1108 12.9797 9.99967 12.9792ZM4.99967 16.6667C4.54134 16.6667 4.14912 16.5036 3.82301 16.1775C3.4969 15.8514 3.33356 15.4589 3.33301 15V13.3333C3.33301 13.0972 3.41301 12.8994 3.57301 12.74C3.73301 12.5806 3.93079 12.5006 4.16634 12.5C4.4019 12.4994 4.59995 12.5794 4.76051 12.74C4.92106 12.9006 5.00079 13.0983 4.99967 13.3333V15H14.9997V13.3333C14.9997 13.0972 15.0797 12.8994 15.2397 12.74C15.3997 12.5806 15.5975 12.5006 15.833 12.5C16.0686 12.4994 16.2666 12.5794 16.4272 12.74C16.5877 12.9006 16.6675 13.0983 16.6663 13.3333V15C16.6663 15.4583 16.5033 15.8508 16.1772 16.1775C15.8511 16.5042 15.4586 16.6672 14.9997 16.6667H4.99967Z" fill="white"/>
                </svg>
            </a>
        </div>
    @else
        <div class="rounded-lg bg-darkslategray-300 overflow-hidden flex flex-row items-center justify-center gap-2">
            <a href="{{ route('lahan.rencana-reklamasi.index', $lahan->lahan_id) }}?tahun={{ $tahun_aktif }}" class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                <span class="relative leading-5 font-medium font-outfit">Back</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.99994 10L3.29294 10.707L2.58594 10L3.29294 9.29303L3.99994 10ZM20.9999 18C20.9999 18.2652 20.8946 18.5196 20.707 18.7071C20.5195 18.8947 20.2652 19 19.9999 19C19.7347 19 19.4804 18.8947 19.2928 18.7071C19.1053 18.5196 18.9999 18.2652 18.9999 18H20.9999ZM8.29294 15.707L3.29294 10.707L4.70694 9.29303L9.70694 14.293L8.29294 15.707ZM3.29294 9.29303L8.29294 4.29303L9.70694 5.70703L4.70694 10.707L3.29294 9.29303ZM3.99994 9.00003H13.9999V11H3.99994V9.00003ZM20.9999 16V18H18.9999V16H20.9999ZM13.9999 9.00003C15.8565 9.00003 17.6369 9.73753 18.9497 11.0503C20.2620 12.363 20.9999 14.1435 20.9999 16H18.9999C18.9999 14.6739 18.4732 13.4022 17.5355 12.4645C16.5978 11.5268 15.326 11 13.9999 11V9.00003Z" fill="white"/>
                </svg>
            </a>
        </div>
    @endif
</div>