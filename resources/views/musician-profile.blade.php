<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Musician Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(Auth::user()->musicianProfile)
                        <div class="flex justify-between items-center mb-4">
                            <p><strong>Status:</strong> {{ Auth::user()->musicianProfile->is_approved ? 'Approved' : 'Pending Approval' }}</p>
                            @if(Auth::user()->musicianProfile->stripe_connect_id)
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Stripe Connected</span>
                            @else
                                <a href="{{ route('stripe.connect.create') }}" class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Connect with Stripe</a>
                            @endif
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <livewire:musician-profile-form />

                    <div class="mt-8">
                        <livewire:multimedia-manager />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
