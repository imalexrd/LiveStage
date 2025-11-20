<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Your Musician Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Profile Status -->
            @if(Auth::user()->musicianProfile)
                <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                    <span class="text-sm font-medium text-gray-600">Profile Status:</span>
                    @if(Auth::user()->musicianProfile->is_approved)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Approved
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                             <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zM10 15a1 1 0 110-2 1 1 0 010 2z" clip-rule="evenodd" />
                            </svg>
                            Pending Approval
                        </span>
                    @endif

                    <div class="mt-4 border-t pt-4">
                        <span class="text-sm font-medium text-gray-600 block mb-2">Payout Configuration:</span>
                        @if(Auth::user()->musicianProfile->stripe_connect_id)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Stripe Connected
                            </span>
                             <a href="{{ route('stripe.connect') }}" class="ml-2 text-sm text-blue-600 hover:text-blue-800 underline">
                                (Update Settings)
                            </a>
                        @else
                            <a href="{{ route('stripe.connect') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Connect with Stripe
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Session Message -->
            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Profile Information Card -->
            <div class="bg-white overflow-hidden shadow-md rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                    <p class="mt-1 text-sm text-gray-600">Update your public profile details here.</p>
                </div>
                <div class="p-6">
                    <livewire:musician-profile-form />
                </div>
            </div>

            <!-- Multimedia Management Card -->
            <div class="bg-white overflow-hidden shadow-md rounded-lg">
                 <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Media Gallery</h3>
                    <p class="mt-1 text-sm text-gray-600">Manage your photos, videos, and audio clips.</p>
                </div>
                <div class="p-6">
                    <livewire:multimedia-manager />
                </div>
            </div>
        </div>
    </div>
    <livewire:location-picker-modal />
</x-app-layout>
