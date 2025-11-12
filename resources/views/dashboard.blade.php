<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            @if(auth()->user()->role === 'client' || auth()->user()->role === 'admin')
                <div class="mt-8">
                    <h3 class="text-lg font-semibold leading-tight text-gray-800">Available Musicians</h3>
                    @livewire('musicians-list')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
