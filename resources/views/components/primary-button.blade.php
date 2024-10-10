<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#95389E] border border-transparent rounded-md font-semibold text-xs text-white hover:bg-[#BC55C3] focus:bg-[#c10070] active:bg-[#b00067] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
