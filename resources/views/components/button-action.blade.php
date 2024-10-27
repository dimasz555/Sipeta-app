<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-custom btn-sm']) }}>
    {{ $slot }}
</button>
