<div x-data="{ 
    openFile: false,
    }"
    class="h-full w-64 fixed overflow-y-auto overflow-x-hidden bg-gainsboro border-darkslategray border-solid border-b-[0px] border-r-[1px] border-l-[0px] border-t-[0px] box-border flex flex-col items-start justify-start pt-8 px-5 pb-5 gap-7 text-left text-sm text-white font-outfit">

    <!-- Home Button -->
    <div class="self-stretch flex flex-col items-start justify-start">
        <a href="{{ route('lahan.index') }}" class="self-stretch shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-darkslategray h-9 overflow-hidden flex flex-row items-center justify-start py-3.5 px-4 box-border gap-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 19V10C4 9.68333 4.071 9.38333 4.213 9.1C4.355 8.81667 4.55067 8.58333 4.8 8.4L10.8 3.9C11.15 3.63333 11.55 3.5 12 3.5C12.45 3.5 12.85 3.63333 13.2 3.9L19.2 8.4C19.45 8.58333 19.646 8.81667 19.788 9.1C19.93 9.38333 20.0007 9.68333 20 10V19C20 19.55 19.804 20.021 19.412 20.413C19.02 20.805 18.5493 21.0007 18 21H15C14.7167 21 14.4793 20.904 14.288 20.712C14.0967 20.52 14.0007 20.2827 14 20V15C14 14.7167 13.904 14.4793 13.712 14.288C13.52 14.0967 13.2827 14.0007 13 14H11C10.7167 14 10.4793 14.096 10.288 14.288C10.0967 14.48 10.0007 14.7173 10 15V20C10 20.2833 9.904 20.521 9.712 20.713C9.52 20.905 9.28267 21.0007 9 21H6C5.45 21 4.97933 20.8043 4.588 20.413C4.19667 20.0217 4.00067 19.5507 4 19Z" fill="currentColor"/>
            </svg>
            <div class="relative leading-5 font-medium">Home</div>
        </a>
    </div>

    <!-- Menu Section -->
    <div class="self-stretch flex flex-col items-start justify-start gap-2.5">
        <div class="self-stretch flex flex-col items-start justify-start gap-4">
            <div class="relative leading-5 uppercase text-xs text-darkslategray">MENU</div>
            
            <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm">
                <!-- Dashboard -->
                <a href="{{ route('detail-lahan.dashboard', ['lahan' => $globalLahanId]) }}"
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('detail-lahan.dashboard') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12.75C10.2426 12.75 11.25 13.7574 11.25 15V18.5C11.25 19.7426 10.2426 20.75 9 20.75H5.5C4.25737 20.75 3.25001 19.7426 3.25 18.5V15C3.25002 13.7574 4.25737 12.75 5.5 12.75H9ZM18.5 12.75C19.7426 12.75 20.75 13.7574 20.75 15V18.5C20.75 19.7426 19.7426 20.75 18.5 20.75H15C13.7574 20.75 12.75 19.7426 12.75 18.5V15C12.75 13.7574 13.7574 12.75 15 12.75H18.5ZM5.5 14.25C5.0858 14.25 4.75002 14.5858 4.75 15V18.5C4.75001 18.9142 5.08579 19.25 5.5 19.25H9C9.41421 19.25 9.74999 18.9142 9.75 18.5V15C9.74998 14.5858 9.4142 14.25 9 14.25H5.5ZM15 14.25C14.5858 14.25 14.25 14.5858 14.25 15V18.5C14.25 18.9142 14.5858 19.25 15 19.25H18.5C18.9142 19.25 19.25 18.9142 19.25 18.5V15C19.25 14.5858 18.9142 14.25 18.5 14.25H15ZM9 3.25C10.2426 3.25 11.25 4.25736 11.25 5.5V9C11.25 10.2426 10.2426 11.25 9 11.25H5.5C4.25737 11.25 3.25001 10.2426 3.25 9V5.5C3.25 4.25736 4.25736 3.25 5.5 3.25H9ZM18.5 3.25C19.7426 3.25 20.75 4.25736 20.75 5.5V9C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 9V5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5ZM5.5 4.75C5.08579 4.75 4.75 5.08579 4.75 5.5V9C4.75001 9.4142 5.08579 9.75 5.5 9.75H9C9.41421 9.75 9.74999 9.4142 9.75 9V5.5C9.75 5.08579 9.41421 4.75 9 4.75H5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V9C14.25 9.4142 14.5858 9.75 15 9.75H18.5C18.9142 9.75 19.25 9.4142 19.25 9V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Dashboard</div>
                    </div>
                </a>

                <!-- Plot Lahan -->
                <a href="{{ route('lahan.plot.index', ['lahan' => $globalLahanId]) }}"
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.plot.*', 'plot.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.5001 4.5C22.4977 3.72206 22.1927 2.97557 21.6495 2.41862C21.1064 1.86168 20.3678 1.53802 19.5901 1.51618C18.8125 1.49434 18.0569 1.77605 17.4833 2.30163C16.9098 2.82722 16.5633 3.55541 16.5173 4.332L7.18956 6.19725C6.97147 5.74397 6.64263 5.35297 6.23345 5.0604C5.82426 4.76784 5.34793 4.58313 4.84847 4.52337C4.34901 4.46361 3.84255 4.53072 3.37589 4.71848C2.90922 4.90625 2.49743 5.20863 2.17855 5.59766C1.85967 5.9867 1.64401 6.44984 1.55149 6.94428C1.45897 7.43872 1.49258 7.9485 1.64922 8.42651C1.80585 8.90452 2.08046 9.33534 2.44765 9.67914C2.81485 10.0229 3.26278 10.2686 3.75006 10.3935V16.6065C3.23826 16.7377 2.77028 17.0019 2.39364 17.3724C2.01699 17.7429 1.7451 18.2065 1.60557 18.7161C1.46604 19.2257 1.46383 19.7631 1.59918 20.2738C1.73452 20.7845 2.0026 21.2503 2.37619 21.6239C2.74978 21.9975 3.21557 22.2655 3.72628 22.4009C4.23698 22.5362 4.77441 22.534 5.28398 22.3945C5.79356 22.255 6.25714 21.9831 6.62765 21.6064C6.99816 21.2298 7.26241 20.7618 7.39356 20.25H13.6066C13.7312 20.7376 13.9768 21.1859 14.3207 21.5534C14.6645 21.9209 15.0955 22.1958 15.5737 22.3527C16.0519 22.5095 16.5619 22.5433 17.0566 22.4508C17.5514 22.3583 18.0148 22.1425 18.404 21.8235C18.7932 21.5044 19.0957 21.0924 19.2835 20.6255C19.4713 20.1585 19.5384 19.6518 19.4785 19.1521C19.4185 18.6524 19.2336 18.1758 18.9407 17.7666C18.6478 17.3573 18.2564 17.0285 17.8028 16.8105L19.6681 7.48275C20.4316 7.44084 21.1502 7.10859 21.6767 6.55405C22.2032 5.99952 22.4978 5.26467 22.5001 4.5ZM19.5001 3C19.7967 3 20.0867 3.08798 20.3334 3.2528C20.5801 3.41762 20.7723 3.65189 20.8859 3.92598C20.9994 4.20007 21.0291 4.50167 20.9712 4.79264C20.9134 5.08361 20.7705 5.35088 20.5607 5.56066C20.3509 5.77044 20.0837 5.9133 19.7927 5.97118C19.5017 6.02906 19.2001 5.99935 18.926 5.88582C18.6519 5.77229 18.4177 5.58003 18.2529 5.33336C18.088 5.08668 18.0001 4.79667 18.0001 4.5C18.0001 4.10218 18.1581 3.72065 18.4394 3.43934C18.7207 3.15804 19.1022 3 19.5001 3ZM3.00006 7.5C3.00006 7.20333 3.08803 6.91332 3.25285 6.66665C3.41767 6.41997 3.65194 6.22771 3.92603 6.11418C4.20012 6.00065 4.50172 5.97095 4.79269 6.02882C5.08366 6.0867 5.35094 6.22956 5.56072 6.43934C5.77049 6.64912 5.91336 6.91639 5.97123 7.20737C6.02911 7.49834 5.99941 7.79994 5.88588 8.07403C5.77234 8.34812 5.58008 8.58238 5.33341 8.74721C5.08674 8.91203 4.79673 9 4.50006 9C4.10223 9 3.7207 8.84197 3.4394 8.56066C3.15809 8.27936 3.00006 7.89783 3.00006 7.5ZM4.50006 21C4.20338 21 3.91337 20.912 3.6667 20.7472C3.42003 20.5824 3.22777 20.3481 3.11424 20.074C3.0007 19.7999 2.971 19.4983 3.02888 19.2074C3.08676 18.9164 3.22962 18.6491 3.4394 18.4393C3.64917 18.2296 3.91645 18.0867 4.20742 18.0288C4.49839 17.9709 4.79999 18.0007 5.07408 18.1142C5.34817 18.2277 5.58244 18.42 5.74726 18.6666C5.91208 18.9133 6.00006 19.2033 6.00006 19.5C6.00006 19.8978 5.84202 20.2794 5.56072 20.5607C5.27941 20.842 4.89788 21 4.50006 21ZM13.6066 18.75H7.39356C7.25878 18.2346 6.98918 17.7643 6.61246 17.3876C6.23574 17.0109 5.76549 16.7413 5.25006 16.6065V10.3935C5.86267 10.2338 6.40885 9.88391 6.81005 9.39417C7.21125 8.90443 7.44679 8.30007 7.48281 7.668L16.8106 5.80275C17.1043 6.40759 17.5926 6.89622 18.1973 7.19025L16.3321 16.5173C15.7 16.5533 15.0956 16.7888 14.6059 17.19C14.1161 17.5912 13.7663 18.1374 13.6066 18.75ZM16.5001 21C16.2034 21 15.9134 20.912 15.6667 20.7472C15.42 20.5824 15.2278 20.3481 15.1142 20.074C15.0007 19.7999 14.971 19.4983 15.0289 19.2074C15.0868 18.9164 15.2296 18.6491 15.4394 18.4393C15.6492 18.2296 15.9164 18.0867 16.2074 18.0288C16.4984 17.9709 16.8 18.0007 17.0741 18.1142C17.3482 18.2277 17.5824 18.42 17.7473 18.6666C17.9121 18.9133 18.0001 19.2033 18.0001 19.5C18.0001 19.8978 17.842 20.2794 17.5607 20.5607C17.2794 20.842 16.8979 21 16.5001 21Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Plot Lahan</div>
                    </div>
                </a>

                <!-- Anggaran -->
                <a href="#" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 16C11.45 16 10.9793 15.8043 10.588 15.413C10.1967 15.0217 10.0007 14.5507 10 14C9.99933 13.4493 10.1953 12.9787 10.588 12.588C10.9807 12.1973 11.4513 12.0013 12 12C12.5487 11.9987 13.0197 12.1947 13.413 12.588C13.8063 12.9813 14.002 13.452 14 14C13.998 14.548 13.8023 15.019 13.413 15.413C13.0237 15.807 12.5527 16.0027 12 16ZM7.375 7H16.625L17.9 4.45C18.0667 4.11667 18.054 3.79167 17.862 3.475C17.67 3.15833 17.3827 3 17 3H7C6.61667 3 6.32933 3.15833 6.138 3.475C5.94667 3.79167 5.934 4.11667 6.1 4.45L7.375 7ZM8.4 21H15.6C17.1 21 18.375 20.4793 19.425 19.438C20.475 18.3967 21 17.1173 21 15.6C21 14.9667 20.8917 14.35 20.675 13.75C20.4583 13.15 20.15 12.6083 19.75 12.125L17.15 9H6.85L4.25 12.125C3.85 12.6083 3.54167 13.15 3.325 13.75C3.10833 14.35 3 14.9667 3 15.6C3 17.1167 3.521 18.396 4.563 19.438C5.605 20.48 6.884 21.0007 8.4 21Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Anggaran</div>
                    </div>
                </a>

                <!-- Data Pohon -->
                <a href="#" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.707 2.293C12.5195 2.10553 12.2652 2.00021 12 2.00021C11.7349 2.00021 11.4805 2.10553 11.293 2.293L7.29302 6.293C7.1414 6.44464 7.04255 6.641 7.01104 6.85311C6.97953 7.06522 7.01703 7.28183 7.11802 7.471C7.36502 7.934 7.75102 8.246 8.12802 8.458L5.29302 11.293C5.10555 11.4805 5.00023 11.7348 5.00023 12C5.00023 12.2652 5.10555 12.5195 5.29302 12.707C5.77102 13.185 6.37502 13.477 6.92702 13.659L4.29302 16.293C4.17228 16.4139 4.08452 16.5636 4.0381 16.7281C3.99169 16.8925 3.98817 17.066 4.02788 17.2322C4.06759 17.3983 4.1492 17.5516 4.26494 17.6772C4.38068 17.8029 4.52668 17.8968 4.68902 17.95C5.37902 18.177 6.09202 18.338 6.80402 18.48C7.58402 18.637 8.55002 18.792 9.64902 18.892L9.05102 20.684C9.00095 20.8343 8.98732 20.9944 9.01125 21.151C9.03518 21.3077 9.096 21.4564 9.18868 21.5849C9.28136 21.7134 9.40325 21.8181 9.54431 21.8903C9.68537 21.9624 9.84157 22 10 22H14C14.1585 22 14.3147 21.9624 14.4557 21.8903C14.5968 21.8181 14.7187 21.7134 14.8114 21.5849C14.904 21.4564 14.9649 21.3077 14.9888 21.151C15.0127 20.9944 14.9991 20.8343 14.949 20.684L14.351 18.892C15.451 18.792 16.417 18.637 17.196 18.481C17.908 18.338 18.626 18.177 19.316 17.949C19.4778 17.8951 19.6231 17.8008 19.7381 17.675C19.8531 17.5491 19.9341 17.396 19.9733 17.23C20.0125 17.0641 20.0086 16.8909 19.962 16.7269C19.9154 16.5629 19.8276 16.4135 19.707 16.293L17.073 13.659C17.625 13.478 18.229 13.185 18.707 12.707C18.8945 12.5195 18.9998 12.2652 18.9998 12C18.9998 11.7348 18.8945 11.4805 18.707 11.293L15.872 8.458C16.249 8.246 16.635 7.934 16.882 7.471C16.983 7.28183 17.0205 7.06522 16.989 6.85311C16.9575 6.641 16.8586 6.44464 16.707 6.293L12.707 2.293Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Data Pohon</div>
                    </div>
                </a>

                <!-- Gudang -->
                <a href="#" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.487 3.138C11.8184 3.0174 12.1816 3.0174 12.513 3.138L20.013 5.865C20.3023 5.9703 20.5523 6.1621 20.7288 6.41435C20.9054 6.6666 21.0001 6.96708 21 7.275V19.5C21 19.8978 20.842 20.2794 20.5607 20.5607C20.2794 20.842 19.8978 21 19.5 21H4.5C4.10218 21 3.72064 20.842 3.43934 20.5607C3.15804 20.2794 3 19.8978 3 19.5V7.275C2.99991 6.96708 3.09458 6.6666 3.27115 6.41435C3.44773 6.1621 3.69766 5.9703 3.987 5.865L11.487 3.138ZM7.5 19.5H9V16.5C9 16.1022 9.15804 15.7206 9.43934 15.4393C9.72064 15.158 10.1022 15 10.5 15H13.5C13.8978 15 14.2794 15.158 14.5607 15.4393C14.842 15.7206 15 16.1022 15 16.5V19.5H16.5V10.5H7.5V19.5ZM18 19.5V10.5C18 10.1022 17.842 9.72064 17.5607 9.43934C17.2794 9.15803 16.8978 9 16.5 9H7.5C7.10218 9 6.72064 9.15803 6.43934 9.43934C6.15804 9.72064 6 10.1022 6 10.5V19.5H4.5V7.275L12 4.548L19.5 7.275V19.5H18Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Gudang</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Administration Section -->
        <div class="self-stretch flex flex-col items-start justify-start gap-4 mt-4">
            <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">ADMINISTRASI</div>
            
            <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm text-white">
                <!-- Rencana Reklamasi -->
                <a href="{{ route('lahan.rencana-reklamasi.index', ['lahan' => $globalLahanId]) }}"
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.rencana-reklamasi.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 3.5C8 3.10218 8.15804 2.72064 8.43934 2.43934C8.72064 2.15804 9.10218 2 9.5 2H14.5C14.8978 2 15.2794 2.15804 15.5607 2.43934C15.842 2.72064 16 3.10218 16 3.5V4.5C16 4.89782 15.842 5.27936 15.5607 5.56066C15.2794 5.84196 14.8978 6 14.5 6H9.5C9.10218 6 8.72064 5.84196 8.43934 5.56066C8.15804 5.27936 8 4.89782 8 4.5V3.5Z" fill="currentColor"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.5 4.03699C5.242 4.10699 4.448 4.30699 3.879 4.87699C3 5.75599 3 7.16999 3 9.99799V15.998C3 18.827 3 20.241 3.879 21.12C4.757 21.998 6.172 21.998 9 21.998H15C17.828 21.998 19.243 21.998 20.121 21.12C21 20.24 21 18.827 21 15.998V9.99799C21 7.16999 21 5.75599 20.121 4.87699C19.552 4.30699 18.758 4.10699 17.5 4.03699V4.49999C17.5 5.29564 17.1839 6.0587 16.6213 6.62131C16.0587 7.18392 15.2956 7.49999 14.5 7.49999H9.5C8.70435 7.49999 7.94129 7.18392 7.37868 6.62131C6.81607 6.0587 6.5 5.29564 6.5 4.49999V4.03699ZM6.25 10.5C6.25 10.3011 6.32902 10.1103 6.46967 9.96966C6.61032 9.82901 6.80109 9.74999 7 9.74999H17C17.1989 9.74999 17.3897 9.82901 17.5303 9.96966C17.671 10.1103 17.75 10.3011 17.75 10.5C17.75 10.6989 17.671 10.8897 17.5303 11.0303C17.3897 11.171 17.1989 11.25 17 11.25H7C6.80109 11.25 6.61032 11.171 6.46967 11.0303C6.32902 10.8897 6.25 10.6989 6.25 10.5ZM7.25 14C7.25 13.8011 7.32902 13.6103 7.46967 13.4697C7.61032 13.329 7.80109 13.25 8 13.25H16C16.1989 13.25 16.3897 13.329 16.5303 13.4697C16.671 13.6103 16.75 13.8011 16.75 14C16.75 14.1989 16.671 14.3897 16.5303 14.5303C16.3897 14.671 16.1989 14.75 16 14.75H8C7.80109 14.75 7.61032 14.671 7.46967 14.5303C7.32902 14.3897 7.25 14.1989 7.25 14ZM8.25 17.5C8.25 17.3011 8.32902 17.1103 8.46967 16.9697C8.61032 16.829 8.80109 16.75 9 16.75H15C15.1989 16.75 15.3897 16.829 15.5303 16.9697C15.671 17.1103 15.75 17.3011 15.75 17.5C15.75 17.6989 15.671 17.8897 15.5303 18.0303C15.3897 18.171 15.1989 18.25 15 18.25H9C8.80109 18.25 8.61032 18.171 8.46967 18.0303C8.32902 17.8897 8.25 17.6989 8.25 17.5Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Rencana Reklamasi</div>
                    </div>
                </a>

                <!-- Rencana Biaya Reklamasi -->
                <a href="{{ route('lahan.rencana-biaya.index', ['lahan' => $globalLahanId]) }}"
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.rencana-biaya.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 16C16 14.895 12.866 14 9 14M16 16C16 17.105 12.866 18 9 18C5.134 18 2 17.105 2 16M16 16V20.937C16 22.076 12.866 23 9 23C5.134 23 2 22.077 2 20.937V16M16 16C19.824 16 23 15.013 23 14V4M9 14C5.134 14 2 14.895 2 16M9 14C4.582 14 1 13.013 1 12V7M9 5C4.582 5 1 5.895 1 7M1 7C1 8.105 4.582 9 9 9C9 10.013 12.253 11 16.077 11C19.901 11 23 10.013 23 9M23 4C23 2.895 19.9 2 16.077 2C12.254 2 9.154 2.895 9.154 4M23 4C23 5.105 19.9 6 16.077 6C12.254 6 9.154 5.105 9.154 4M9.154 4V14.166" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Rencana Biaya</div>
                    </div>
                </a>

                <!-- Rekapitulasi Pelaksanaan -->
                <a href="{{ route('lahan.rekapitulasi-reklamasi.index', ['lahan' => $globalLahanId]) }}" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.rekapitulasi-reklamasi.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 17V15M12 17V13M15 17V11M17 21H7C6.46957 21 5.96086 20.7893 5.58579 20.4142C5.21071 20.0391 5 19.5304 5 19V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H12.586C12.8512 3.00006 13.1055 3.10545 13.293 3.293L18.707 8.707C18.8946 8.89449 18.9999 9.1488 19 9.414V19C19 19.5304 18.7893 20.0391 18.4142 20.4142C18.0391 20.7893 17.5304 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Rekapitulasi Reklamasi</div>
                    </div>
                </a>

                <!-- Rekapitulasi Biaya -->
                <a href="{{ route('lahan.rekapitulasi-biaya.index', ['lahan' => $globalLahanId]) }}" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.rekapitulasi-biaya.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.25 5C3.25 4.27065 3.53973 3.57118 4.05546 3.05546C4.57118 2.53973 5.27065 2.25 6 2.25H16C16.7293 2.25 17.4288 2.53973 17.9445 3.05546C18.4603 3.57118 18.75 4.27065 18.75 5V10.5C18.75 10.6989 18.671 10.8897 18.5303 11.0303C18.3897 11.171 18.1989 11.25 18 11.25C17.8011 11.25 17.6103 11.171 17.4697 11.0303C17.329 10.8897 17.25 10.6989 17.25 10.5V5C17.25 4.31 16.69 3.75 16 3.75H6C5.31 3.75 4.75 4.31 4.75 5V19C4.75 19.69 5.31 20.25 6 20.25H13.5C13.6989 20.25 13.8897 20.329 14.0303 20.4697C14.171 20.6103 14.25 20.8011 14.25 21C14.25 21.1989 14.171 21.3897 14.0303 21.5303C13.8897 21.671 13.6989 21.75 13.5 21.75H6C5.27065 21.75 4.57118 21.4603 4.05546 20.9445C3.53973 20.4288 3.25 19.7293 3.25 19V5Z" fill="currentColor"/>
                            <path d="M18 13.25C18.1989 13.25 18.3897 13.329 18.5303 13.4697C18.671 13.6103 18.75 13.8011 18.75 14V14.418C19.777 14.456 20.75 15.213 20.75 16.333C20.75 16.5319 20.671 16.7227 20.5303 16.8633C20.3897 17.004 20.1989 17.083 20 17.083C19.8011 17.083 19.6103 17.004 19.4697 16.8633C19.329 16.7227 19.25 16.5319 19.25 16.333C19.25 16.191 19.088 15.917 18.669 15.917H17.163C17.0435 15.9138 16.9267 15.9535 16.834 16.029C16.766 16.089 16.75 16.146 16.75 16.183C16.7485 16.2333 16.7545 16.2835 16.768 16.332C16.7813 16.3418 16.7953 16.3505 16.81 16.358C16.85 16.378 16.91 16.402 17.003 16.43L19.421 17.131C19.697 17.213 20.051 17.349 20.329 17.644C20.629 17.964 20.75 18.374 20.75 18.817C20.75 19.884 19.794 20.583 18.837 20.583H18.75V21C18.75 21.1989 18.671 21.3897 18.5303 21.5303C18.3897 21.671 18.1989 21.75 18 21.75C17.8011 21.75 17.6103 21.671 17.4697 21.5303C17.329 21.3897 17.25 21.1989 17.25 21V20.582C16.223 20.544 15.25 19.787 15.25 18.667C15.25 18.4681 15.329 18.2773 15.4697 18.1367C15.6103 17.996 15.8011 17.917 16 17.917C16.1989 17.917 16.3897 17.996 16.5303 18.1367C16.671 18.2773 16.75 18.4681 16.75 18.667C16.75 18.809 16.912 19.083 17.331 19.083H18.837C18.9565 19.0862 19.0733 19.0465 19.166 18.971C19.234 18.911 19.25 18.854 19.25 18.817C19.2515 18.7667 19.2455 18.7165 19.232 18.668C19.2187 18.6582 19.2047 18.6495 19.19 18.642C19.15 18.622 19.09 18.598 18.997 18.57L16.579 17.869C16.303 17.787 15.949 17.651 15.671 17.356C15.371 17.036 15.25 16.626 15.25 16.183C15.25 15.116 16.206 14.417 17.163 14.417H17.25V14C17.25 13.8011 17.329 13.6103 17.4697 13.4697C17.6103 13.329 17.8011 13.25 18 13.25ZM6.25 11C6.25 10.8011 6.32902 10.6103 6.46967 10.4697C6.61032 10.329 6.80109 10.25 7 10.25H7.5C7.69891 10.25 7.88968 10.329 8.03033 10.4697C8.17098 10.6103 8.25 10.8011 8.25 11C8.25 11.1989 8.17098 11.3897 8.03033 11.5303C7.88968 11.671 7.69891 11.75 7.5 11.75H7C6.80109 11.75 6.61032 11.671 6.46967 11.5303C6.32902 11.3897 6.25 11.1989 6.25 11ZM9.25 11C9.25 10.8011 9.32902 10.6103 9.46967 10.4697C9.61032 10.329 9.80109 10.25 10 10.25H15C15.1989 10.25 15.3897 10.329 15.5303 10.4697C15.671 10.6103 15.75 10.8011 15.75 11C15.75 11.1989 15.671 11.3897 15.5303 11.5303C15.3897 11.671 15.1989 11.75 15 11.75H10C9.80109 11.75 9.61032 11.671 9.46967 11.5303C9.32902 11.3897 9.25 11.1989 9.25 11ZM6.25 14C6.25 13.8011 6.32902 13.6103 6.46967 13.4697C6.61032 13.329 6.80109 13.25 7 13.25H7.5C7.69891 13.25 7.88968 13.329 8.03033 13.4697C8.17098 13.6103 8.25 13.8011 8.25 14C8.25 14.1989 8.17098 14.3897 8.03033 14.5303C7.88968 14.671 7.69891 14.75 7.5 14.75H7C6.80109 14.75 6.61032 14.671 6.46967 14.5303C6.32902 14.3897 6.25 14.1989 6.25 14ZM9.25 14C9.25 13.8011 9.32902 13.6103 9.46967 13.4697C9.61032 13.329 9.80109 13.25 10 13.25H13.5C13.6989 13.25 13.8897 13.329 14.0303 13.4697C14.171 13.6103 14.25 13.8011 14.25 14C14.25 14.1989 14.171 14.3897 14.0303 14.5303C13.8897 14.671 13.6989 14.75 13.5 14.75H10C9.80109 14.75 9.61032 14.671 9.46967 14.5303C9.32902 14.3897 9.25 14.1989 9.25 14ZM6.25 17C6.25 16.8011 6.32902 16.6103 6.46967 16.4697C6.61032 16.329 6.80109 16.25 7 16.25H7.5C7.69891 16.25 7.88968 16.329 8.03033 16.4697C8.17098 16.6103 8.25 16.8011 8.25 17C8.25 17.1989 8.17098 17.3897 8.03033 17.5303C7.88968 17.671 7.69891 17.75 7.5 17.75H7C6.80109 17.75 6.61032 17.671 6.46967 17.5303C6.32902 17.3897 6.25 17.1989 6.25 17ZM9.25 17C9.25 16.8011 9.32902 16.6103 9.46967 16.4697C9.61032 16.329 9.80109 16.25 10 16.25H12C12.1989 16.25 12.3897 16.329 12.5303 16.4697C12.671 16.6103 12.75 16.8011 12.75 17C12.75 17.1989 12.671 17.3897 12.5303 17.5303C12.3897 17.671 12.1989 17.75 12 17.75H10C9.80109 17.75 9.61032 17.671 9.46967 17.5303C9.32902 17.3897 9.25 17.1989 9.25 17ZM6 6.5C6 6.034 6 5.801 6.076 5.617C6.17771 5.37246 6.37227 5.17826 6.617 5.077C6.801 5 7.034 5 7.5 5H14.5C14.966 5 15.199 5 15.383 5.076C15.6275 5.17771 15.8217 5.37227 15.923 5.617C16 5.801 16 6.034 16 6.5C16 6.966 16 7.199 15.924 7.383C15.8223 7.62754 15.6277 7.82174 15.383 7.923C15.199 8 14.966 8 14.5 8H7.5C7.034 8 6.801 8 6.617 7.924C6.37246 7.82229 6.17826 7.62773 6.077 7.383C6 7.199 6 6.966 6 6.5Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Rekapitulasi Biaya</div>
                    </div>
                </a>

                <!-- Kriteria Keberhasilan -->
                <a href="{{ route('lahan.kriteria-keberhasilan.show', ['lahan' => $globalLahanId]) }}" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', 'lahan.kriteria-keberhasilan.*') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.879 6.99999H6C5.46957 6.99999 4.96086 7.2107 4.58579 7.58578C4.21071 7.96085 4 8.46956 4 8.99999V15C4 15.5304 4.21071 16.0391 4.58579 16.4142C4.96086 16.7893 5.46957 17 6 17H18C18.5304 17 19.0391 16.7893 19.4142 16.4142C19.7893 16.0391 20 15.5304 20 15V9.12099L15.56 13.561C15.277 13.8341 14.898 13.9851 14.5047 13.9815C14.1114 13.9779 13.7353 13.82 13.4573 13.5417C13.1793 13.2635 13.0217 12.8872 13.0185 12.4939C13.0153 12.1006 13.1666 11.7218 13.44 11.439L17.879 6.99999ZM6 10.5C6 10.3674 6.05268 10.2402 6.14645 10.1464C6.24021 10.0527 6.36739 9.99999 6.5 9.99999H8.5C8.63261 9.99999 8.75979 10.0527 8.85355 10.1464C8.94732 10.2402 9 10.3674 9 10.5C9 10.6326 8.94732 10.7598 8.85355 10.8535C8.75979 10.9473 8.63261 11 8.5 11H6.5C6.36739 11 6.24021 10.9473 6.14645 10.8535C6.05268 10.7598 6 10.6326 6 10.5ZM6 13.5C6 13.3674 6.05268 13.2402 6.14645 13.1464C6.24021 13.0527 6.36739 13 6.5 13H11C11.1326 13 11.2598 13.0527 11.3536 13.1464C11.4473 13.2402 11.5 13.3674 11.5 13.5C11.5 13.6326 11.4473 13.7598 11.3536 13.8535C11.2598 13.9473 11.1326 14 11 14H6.5C6.36739 14 6.24021 13.9473 6.14645 13.8535C6.05268 13.7598 6 13.6326 6 13.5ZM19.854 7.85399C19.9479 7.7601 20.0006 7.63277 20.0006 7.49999C20.0006 7.36722 19.9479 7.23988 19.854 7.14599C19.7601 7.0521 19.6328 6.99936 19.5 6.99936C19.3672 6.99936 19.2399 7.0521 19.146 7.14599L14.146 12.146C14.0521 12.2399 13.9994 12.3672 13.9994 12.5C13.9994 12.6328 14.0521 12.7601 14.146 12.854C14.2399 12.9479 14.3672 13.0006 14.5 13.0006C14.6328 13.0006 14.7601 12.9479 14.854 12.854L19.854 7.85399Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Kriteria Keberhasilan</div>
                    </div>
                </a>

                <!-- Dokumentasi -->
                <a href="#" 
                   class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                   {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.2773 2C15.8741 2 16.4472 2.23723 16.8691 2.65918L19.3418 5.13184L19.4912 5.29688C19.8191 5.69721 20 6.20063 20 6.72266V16.75C20 17.9926 18.9926 19 17.75 19H16.248V19.75C16.2478 20.9924 15.2405 22 13.998 22H6.24805C5.00557 22 3.99831 20.9924 3.99805 19.75V7.24902C3.99805 6.00638 5.00541 4.99902 6.24805 4.99902H7.00391V4.25C7.00391 3.00736 8.01127 2 9.25391 2H15.2773ZM6.24805 6.49902C5.83383 6.49902 5.49805 6.83481 5.49805 7.24902V19.75C5.49831 20.164 5.834 20.5 6.24805 20.5H13.998C14.4121 20.5 14.7478 20.164 14.748 19.75V19H9.25391C8.01127 19 7.00391 17.9926 7.00391 16.75V6.49902H6.24805ZM9.25391 3.5C8.83969 3.5 8.50391 3.83579 8.50391 4.25V16.75C8.50391 17.1642 8.83969 17.5 9.25391 17.5H17.75C18.1642 17.5 18.5 17.1642 18.5 16.75V6.72266C18.5 6.57342 18.4556 6.42898 18.374 6.30664L18.2803 6.19238L15.8076 3.71973C15.667 3.57919 15.4762 3.5 15.2773 3.5H9.25391Z" fill="currentColor"/>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Dokumentasi</div>
                    </div>
                </a>

                <!-- File Dropdown -->
                <div class="self-stretch flex flex-col items-start justify-start gap-2">
                    <div @click="openFile = !openFile" 
                         class="self-stretch rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 gap-3 cursor-pointer hover:bg-white hover:text-darkslategray ease-in-out">
                        <div class="flex-1 flex flex-row items-center justify-start gap-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L12.117 2.007C12.3402 2.03332 12.5481 2.13408 12.707 2.29301C12.8659 2.45194 12.9667 2.65978 12.993 2.883L13 3V7L13.005 7.15C13.0408 7.62617 13.2458 8.07383 13.5829 8.41203C13.92 8.75023 14.3669 8.95666 14.843 8.994L15 9H19L19.117 9.007C19.3402 9.03332 19.5481 9.13408 19.707 9.29301C19.8659 9.45194 19.9667 9.65978 19.993 9.883L20 10V19C20 19.7652 19.7077 20.5015 19.1827 21.0583C18.6578 21.615 17.9399 21.9501 17.176 21.995L17 22H7C6.23479 22 5.49849 21.7077 4.94174 21.1827C4.38499 20.6578 4.04989 19.9399 4.005 19.176L4 19V5C3.99996 4.23479 4.29233 3.49849 4.81728 2.94174C5.34224 2.38499 6.06011 2.04989 6.824 2.005L7 2H12Z" fill="currentColor"/>
                                <path d="M19 7.00002H15L14.999 2.99902L19 7.00002Z" fill="currentColor"/>
                            </svg>
                            <div class="flex-1 relative leading-5 font-medium">File</div>
                        </div>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-200" :class="{'rotate-180': openFile}">
                            <path d="M4.79175 7.39581L10.0001 12.6041L15.2084 7.39581" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="openFile" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="w-full flex flex-col items-start justify-start pt-0 pb-1 pl-[35px] pr-0 box-border gap-1">
                        
                        <a href="#" 
                            class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                            {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                            <div class="flex-1 relative leading-5 font-medium">Rencana Reklamasi</div>
                        </a>
                        
                        <a href="#" 
                            class="self-stretch rounded-lg flex flex-row items-center justify-start py-2 px-3 no-underline hover:bg-white hover:text-darkslategray ease-in-out
                            {{ request()->routeIs('expense.*', '#') ? 'bg-white !text-darkslategray' : 'bg-darkslategray text-white' }}">
                            <div class="flex-1 relative leading-5 font-medium">Laporan Pelaksanaan</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Setting Section -->
        <div class="lg:hidden self-stretch flex flex-col items-start justify-start gap-4 mt-4">
            <div class="self-stretch relative leading-5 uppercase text-xs text-darkslategray">SETTING</div>

            <div class="self-stretch flex flex-col items-start justify-start gap-1 text-sm text-white">
                <!-- Profile Link -->
                <a type="button" href="{{ route('profile.edit') }}" class="self-stretch rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white hover:bg-white hover:text-darkslategray ease-in-out">
                    <div class="flex-1 flex flex-row items-center justify-start gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <div class="flex-1 relative leading-5 font-medium">Profile</div>
                    </div>
                </a>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full border-none rounded-lg bg-darkslategray flex flex-row items-center justify-start py-2 px-3 no-underline text-white font-outfit font-medium hover:bg-white hover:text-darkslategray ease-in-out">
                        <div class="flex-1 flex flex-row items-center justify-start gap-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 8V6C14 5.46957 13.7893 4.96086 13.4142 4.58579C13.0391 4.21071 12.5304 4 12 4H5C4.46957 4 3.96086 4.21071 3.58579 4.58579C3.21071 4.96086 3 5.46957 3 6V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H12C12.5304 20 13.0391 19.7893 13.4142 19.4142C13.7893 19.0391 14 18.5304 14 18V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 12H21M21 12L18 9M21 12L18 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Logout</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>