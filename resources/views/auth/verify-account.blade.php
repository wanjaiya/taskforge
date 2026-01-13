<x-guest-layout>
    <!-- Session Status -->

        

                <div class="mb-4">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <x-session-error-status class="mb-4" :error="session('error')" />

                    <div class="mb-4 text-md text-gray-600 dark:text-white">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by entering the code we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    <form method="POST" action="{{ route('verify.account.process') }}" class="w-full">
                        @csrf

                        <!-- Verification Code -->
                        <div>

                            <label class="text-slate-900 text-[15px] font-medium mb-2 block dark:text-white">Verification Code</label>
                            <input id="verification_code" required
                                class="w-full text-sm text-slate-900 dark:text-white  bg-transparent pl-4 pr-10 py-3.5 rounded-md border border-gray-200 focus:border-blue-600 outline-none"
                                type="text" name="verification_code" :value="old('verification_code')" required
                                autofocus autocomplete="one-time-code" />
                            <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="w-full py-2.5 px-4 text-[15px] font-medium tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none cursor-pointer">
                                Verify Account
                            </button>
                        </div>

                </div>

             
           
    



</x-guest-layout>
