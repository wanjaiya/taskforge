<aside class="w-64 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <nav class="mt-4">
        <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-gray-600 dark:text-white hover:bg-blue-500 hover:text-white" >Dashboard</a>


        @can('manage-users')
            <a href="{{ route('users.index') }}" class="block py-2 px-4 text-gray-600 dark:text-white hover:bg-blue-500 hover:text-white" >Users</a>
             <a href="{{ route('roles.index') }}" class="block py-2 px-4 text-gray-600 dark:text-white hover:bg-blue-500 hover:text-white" >Roles</a>
            <a href="{{ route('permissions.index') }}" class="block py-2 px-4 text-gray-600 dark:text-white hover:bg-blue-500 hover:text-white" >Permissions</a>
           
        @endcan
    </nav>
</aside>
