@props(['plot'])

<div class="bg-white overflow-hidden shadow-md rounded-lg sm:rounded-lg mb-6">
    <div class="p-6 font-outfit">
        <div class="flex flex-row items-center justify-between gap-4">
            <div class="flex-1 flex flex-col items-start justify-start gap-1">
                <div class="relative text-lg leading-7 font-semibold text-gray">
                    QR Code Plot
                </div>
                <div class="relative text-sm leading-5 text-slategray">
                    Scan QR Code dengan smartphone untuk akses informasi plot lahan.
                </div>
            </div>
            <x-plot.scan.qrcode-modal :plot="$plot" />
        </div>
    </div>
</div>