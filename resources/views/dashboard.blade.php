<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                    
                    
                 
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasCompany() == false)

  
        <x-modal name="company-form" :show="true">
            <div x-data="permissionForm()" x-on:open-permission-form.window="open($event.detail)">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white pl-6 py-6 text-center" >
                    Create a company or organization
                </h2>
                <form method="POST" action="{{ route('companies.store') }}" class="p-6 pt-2" enctype="multipart/form-data">
                    @csrf

                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" autofocus required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div class="mt-4">
                        <x-input-label for="slug" :value="__('Logo')" />
                        <input class="block mt-1 w-full" type="file" name="logo" required accept="image/*"/>

                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <!-- Group -->
                    <div class="mt-4">
                        <label for="emails">Invite users via emails (comma-separated):</label>
                        <x-text-input id="group" class="block mt-1 w-full" type="text" name="emails"
                            id="emails" multiple />
                        <x-input-error :messages="$errors->get('users')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-secondary-button x-on:click="$dispatch('close-modal', '{{ 'company-form' }}')"
                            class="mr-3">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button class="ml-3">
                            Create
                            
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endif


    @push('scripts')
        <script>
            $(function() {
                const input = document.getElementById('emails');
                const emails = input.value.split(',').map(e => e.trim());
            })
        </script>
    @endpush
</x-app-layout>
