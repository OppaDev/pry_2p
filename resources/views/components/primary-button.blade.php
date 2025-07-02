<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-xl font-semibold text-base text-white tracking-wide hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2 active:from-blue-800 active:to-blue-900 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl btn-soft-transition']) }}>
    {{ $slot }}
</button>
