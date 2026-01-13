@props(['info'])

@if ($info)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm bg-blue-200 text-blue-800 dark:text-blue-800 p-6']) }}>
        {{ $info }}
    </div>
@endif
