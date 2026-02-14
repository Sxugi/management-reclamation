<x-guest-layout>
    <div class="w-full bg-whitesmoke-100 overflow-x-hidden flex flex-col items-center justify-center min-h-screen py-0 px-0 md:py-20 lg:py-[180px] md:px-12 lg:px-24 xl:px-[120px] box-border text-left text-whitesmoke font-outfit">
        
        <div class="bg-darkslategray w-full min-h-screen md:min-h-0 max-w-none md:max-w-[500px] lg:max-w-lg flex flex-col items-center justify-center py-8 md:py-12 lg:py-[54px] px-4 sm:px-6 md:px-8 lg:px-[86px] box-border rounded-none md:rounded-[25px]">
            
            <div class="w-full flex flex-col items-center justify-center gap-6 md:gap-8">
                <div class="self-stretch flex flex-col items-start justify-start gap-2 md:gap-3">
                    <div class="relative text-2xl md:text-3xl lg:text-4xl leading-tight md:leading-[44px] font-semibold">Log In</div>
                    <div class="relative text-sm leading-5 text-darkgray">Enter your email / username and password to log in!</div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="self-stretch flex flex-col items-center justify-center text-sm text-gray-100 w-full">
                    @csrf
                    <div class="w-full flex flex-col items-start justify-start gap-4 md:gap-5">
                        <div class="w-full flex flex-col items-start justify-start">
                            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                                <label for="email" class="relative leading-5 font-medium">
                                    Email / Username
                                    <span class="text-tomato">*</span>
                                </label>
                                <x-main.text-input 
                                    id="email" 
                                    name="email" 
                                    type="text" 
                                    placeholder="Enter your email or username" 
                                    required autofocus
                                    oninvalid="this.setCustomValidity('Username or Email is required')" 
                                    oninput="this.setCustomValidity('')"
                                    autocomplete="username" 
                                    class="flex-1 relative leading-5 focus:ring-0 py-3 text-gray-200 w-full"
                                />
                                <x-main.input-error :messages="$errors->get('email')" data-turbo-temporary class="mt-2" />
                            </div>
                        </div>

                        <div class="w-full flex flex-col items-start justify-start">
                            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                                <label for="password" class="relative leading-5 font-medium">
                                    Password
                                    <span class="text-tomato">*</span>
                                </label>
                                <x-main.password-input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    placeholder="Enter your password" 
                                    required 
                                    oninvalid="this.setCustomValidity('Password is required')" 
                                    oninput="this.setCustomValidity('')"
                                    autocomplete="current-password" 
                                    class="flex-1 relative leading-5 focus:ring-0 py-3 text-gray-200 w-full"
                                />
                                <x-main.input-error :messages="$errors->get('password')" data-turbo-temporary class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex flex-row flex-wrap items-center justify-between gap-2 text-slategray-100 mt-4 font-outfit">
                        <div class="flex flex-row items-center justify-start gap-3">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-lightgray border-solid border-[1.3px] w-5 h-5 shrink-0 focus:ring-0 cursor-pointer" />
                            <label for="remember_me" class="relative leading-5">Keep me logged in</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="relative leading-5 text-mediumslateblue no-underline">Forgot password?</a>
                    </div>

                    <div class="w-full flex flex-col items-start justify-start mt-6">
                        <x-main.primary-button type="submit" class="w-full items-center justify-center py-3 bg-slategray-200 hover:!bg-gainsboro hover:text-darkslategray border-none cursor-pointer">
                            <span class="relative leading-5 font-medium">Log In</span>
                        </x-main.primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('turbo:load', function() {
            const togglePasswordVisibility = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');
            
            if (togglePasswordVisibility && passwordInput) {
                togglePasswordVisibility.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const iconSrc = type === 'password' 
                        ? '/images/eye-enabled.svg' 
                        : '/images/eye-disabled.svg'; 
                        
                    this.setAttribute('src', iconSrc);
                });
            }
        });
    </script>
</x-guest-layout>