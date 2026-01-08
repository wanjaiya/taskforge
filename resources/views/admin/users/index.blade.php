<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>
    <div class="py-8">


        <div class="mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="mb-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />

            </div>

            @can('access-admin-panel')
            <button x-data x-on:click="$dispatch('open-user-form')" class="px-4 py-2 bg-indigo-600 text-white rounded">
                Add User
            </button>
            @endcan
        </div>

        

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 px-2 text-gray-900 dark:text-gray-100">
                <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Role
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Email Verified on
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>

                                   
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                       $names = array_map(fn($item)=> "<span class='bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs'>$item</span>", $user->role_names);
                                   @endphp

                                    {!! implode(' ', $names) !!}
                                    </td>

                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not Verified' }}

                                    </td>

                                    
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if ($user->email_verified_at === null)
                                                <a href="{{ route('users.verify', ['user' => $user->id]) }}"
                                                    class="text-indigo-600 hover:bg-indigo-700 hover:text-white px-3 py-1 rounded">
                                                    Verify
                                                </a>
                                            @endif
                                            <button x-data
                                                x-on:click="$dispatch('open-user-form', {
                                                    name: '{{ $user->name }}',
                                                    email: '{{ $user->email }}',
                                                    role: '{{ $roles }}',
                                                    role_name: '{{ $user->roles->first()?->name }}',
                                                    updateUrl: '{{ route('users.update', $user) }}'
                                                })"
                                                class="text-green-600 hover:bg-green-700 hover:text-white px-3 py-1 rounded">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:bg-red-700 hover:text-white px-3 py-1 rounded"
                                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                                    Delete
                                                </button>
                                            </form>
                                            
                                        </td>
                                   

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>



    <x-modal name="user-form" :show="$errors->userForm->isNotEmpty()">
        <div x-data="userForm()" x-on:open-user-form.window="open($event.detail)">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white pl-6 py-6" x-text="mode === 'create' ? 'Create User' : 'Edit User'">
        </h2>
        <form method="POST" :action="action" class="p-6 pt-2">
            @csrf

            <template x-if="mode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus x-model="form.name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required x-model="form.email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" 
                    x-model="form.password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Password Confirmation -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" x-model="form.password_confirmation" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Role -->
            <div class="mt-4">
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" x-model="form.role"
                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" :selected="form.role_name === '{{ $role->name }}'">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-secondary-button x-on:click="$dispatch('close-modal', '{{ 'user-form' }}')" class="mr-3">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    <span x-text="mode === 'create' ? 'Create' : 'Update'"></span>
                </x-primary-button>
            </div>
        </form>
        </div>
    </x-modal>



    <script>
        function userForm() {
          

            return {
                mode: 'create',
                action: '',
                form: {
                    name: '',
                    email: '',
                    role: '',
                    role_name: '',
                    password: '',
                    password_confirmation: ''
                },

               
                open(data = null) {

                    console.log(data.role_name);
                    if (Object.keys(data || {}).length > 0) {
                        // EDIT
                        this.mode = 'edit'
                        this.action = data.updateUrl
                        this.form.name = data.name
                        this.form.email = data.email
                        this.form.role = data.role
                        this.form.password = ''
                        this.form.password_confirmation = ''
                    } else {
                        // CREATE
                        this.mode = 'create'
                        this.action = '{{ route('users.store') }}'
                        this.form.name = ''
                        this.form.email = ''
                        this.form.role = ''
                        this.form.password = ''
                        this.form.password_confirmation = ''
                    }

                    this.$dispatch('open-modal', 'user-form')
                }

            }

            
        }
    </script>
</x-app-layout>
