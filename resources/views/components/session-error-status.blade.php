@props(['error'])

@if ($error)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm bg-red-200 text-red-800 dark:text-red-800 p-6']) }}>
        {{ $error }}
    </div>
@endif
