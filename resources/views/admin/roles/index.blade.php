<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Roles Management') }}
        </h2>
    </x-slot>
    <div class="py-8">


        <div class="mx-auto sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="mb-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />

            </div>

            @can('manage-users')
                <button x-data x-on:click="$dispatch('open-role-form')" class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Add Role
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
                                Slug
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Permissions
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Actions
                            </th>
                        </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $role->name }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $role->slug }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                   @php
                                       $names = array_map(fn($item)=> "<span class='bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs'>$item</span>", $role->permission_names);
                                   @endphp

                                    {!! implode(' ', $names) !!}


                                </td>

                                @can('access-manager-panel')
                                    <td class="px-6 py-4 whitespace-nowrap  text-sm">
                                        <button x-data
                                                x-on:click="$dispatch('open-role-form', {
                                                    name: '{{ $role->name }}',
                                                    slug: '{{ $role->slug }}',
                                                    permissions: '{{ $permissions }}',
                                                    permission_names: '{{ json_encode($role->permission_names) }}',
                                                    updateUrl: '{{ route('roles.update', $role) }}'
                                                })"
                                                class="text-green-600 hover:bg-green-700 hover:text-white px-3 py-1 rounded">
                                            Edit
                                        </button>

                                        <form action="{{ route('roles.destroy', ['role' => $role->id]) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:bg-red-700 hover:text-white px-3 py-1 rounded"
                                                    onclick="return confirm('Are you sure you want to delete this role?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                @endcan

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>





    <x-modal name="role-form" :show="$errors->roleCreate->isNotEmpty()">
        <div x-data="roleForm()" x-on:open-role-form.window="open($event.detail)">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white pl-6 py-6" x-text="mode === 'create' ? 'Create Role' : 'Edit Role'">
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
                                  autofocus x-model="form.name" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Slug -->
                <div class="mt-4">
                    <x-input-label for="slug" :value="__('Slug')" />
                    <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug')"
                                  x-model="form.slug" required x-bind:readonly="mode === 'edit'"
                    />
                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                </div>

                <!-- Group -->
                <div class="mt-4">
                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">Permissions</h3>

                    @foreach ($permissions->groupBy('group') as $group => $items)
                        <div class="mt-2">
                            <strong class="text-gray-900 dark:text-white">{{ ucfirst($group) }}</strong>

                            @foreach ($items as $permission)
                                <label class="block text-gray-900 dark:text-white">
                                  
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               :checked="form.permission_names.includes('{{ $permission->name }}')"
                                        >
                                    
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close-modal', '{{ 'role-form' }}')" class="mr-3">
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
        function roleForm() {


            return {
                mode: 'create',
                action: '',
                form: {
                    name: '',
                    slug: '',
                    permissions: '',
                    permission_names: []
                },


                open(data = null) {
                    if (Object.keys(data || {}).length > 0) {
                        // EDIT
                        this.mode = 'edit'
                        this.action = data.updateUrl
                        this.form.name = data.name
                        this.form.slug = data.slug
                        this.form.permissions = data.permissions
                        this.form.permission_names = data.permission_names
                    } else {
                        // CREATE
                        this.mode = 'create'
                        this.action = '{{ route('roles.store') }}'
                        this.form.name = ''
                        this.form.slug = ''
                        this.form.permissions = ''
                        this.form.permission_names = []

                    }

                    this.$dispatch('open-modal', 'role-form')
                }

            }


        }
    </script>
</x-app-layout>
