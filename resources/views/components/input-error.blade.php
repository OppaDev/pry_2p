@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-500 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-xs"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
