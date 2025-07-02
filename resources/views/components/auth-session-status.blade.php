@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center p-4 mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl']) }} data-status="true">
        <i class="fas fa-check-circle mr-3 text-green-500"></i>
        <span class="font-medium">{{ $status }}</span>
    </div>
@endif
