<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Initiate') }} → {{ $crimeDetails->description }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Success Message --}}
            @isset($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong>{{ $message }}</strong>
                </div>
            @endisset

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-400 text-red-800 rounded-md p-4">
                    <p class="font-semibold mb-2">{{ __('Please correct the following errors:') }}</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Crime Details Card --}}
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-600">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        {{ __('Crime Details') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">{{ __('Crime Name') }}</p>
                            <p class="font-semibold text-gray-900">{{ $crimeDetails->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Potential Reward') }}</p>
                            <p class="font-semibold text-green-600">¥{{ number_format($crimeDetails->min_money) }} - ¥{{ number_format($crimeDetails->max_money) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Required Crew Size') }}</p>
                            <p class="font-semibold text-gray-900">{{ $crimeDetails->required_gang_size }} {{ __('members') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Required Weapons') }}</p>
                            <p class="font-semibold text-gray-900">{{ $crimeDetails->required_weapons }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">{{ __('Required Cars') }}</p>
                            <p class="font-semibold text-gray-900">{{ $crimeDetails->required_cars }}</p>
                        </div>
                        @if($crimeDetails->required_money != 0)
                            <div>
                                <p class="text-gray-600">{{ __('Upfront Cost') }}</p>
                                <p class="font-semibold text-red-600">¥{{ number_format($crimeDetails->required_money) }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('The weapons, cars and money used in this crime will be stored in a stashhouse while the crime is ongoing.') }}
                        </p>
                    </div>
                    @if($crimeDetails->required_money != 0)
                        <div class="mt-2 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm text-yellow-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('This crime costs') }} ¥{{ number_format($crimeDetails->required_money) }} {{ __('to start and will be deducted once all members accept.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Selection Form --}}
            <form method="POST" action="/gang/crime/start/letsgo/" class="space-y-6">
                @csrf
                <input type="hidden" name="crime_id" value="{{ $crimeDetails->id }}">

                {{-- Gang Members Selection --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                            {{ __('Select Crew Members') }}
                            <span class="text-sm font-normal text-gray-600 ml-2">({{ __('Required:') }} {{ $crimeDetails->required_gang_size }})</span>
                        </h3>
                        
                        @if(count($availableGangMembers) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($availableGangMembers as $member)
                                    <label class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all duration-150">
                                        <input type="checkbox" name="gang_members[]" value="{{ $member->id }}" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <div class="ml-3 flex-1">
                                            <p class="font-semibold text-gray-900">{{ $member->name }}</p>
                                            <div class="flex gap-3 text-xs text-gray-600 mt-1">
                                                <span>{{ __('HP') }}: {{ $member->health }}</span>
                                                <span>{{ __('EXP') }}: {{ number_format($member->exp) }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 italic">{{ __('No available gang members in your location.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Cars Selection --}}
                @if($crimeDetails->required_cars > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                                {{ __('Select Cars') }}
                                <span class="text-sm font-normal text-gray-600 ml-2">({{ __('Required:') }} {{ $crimeDetails->required_cars }})</span>
                            </h3>
                            
                            @if(count($availableCars) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($availableCars as $car)
                                        <label class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all duration-150">
                                            <input type="checkbox" name="gang_cars[]" value="{{ $car->id }}" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <div class="ml-3 flex-1">
                                                <p class="font-semibold text-gray-900">{{ $car->name }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 italic">{{ __('No available cars. You may need to purchase cars for this crime.') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Weapons Selection --}}
                @if($crimeDetails->required_weapons > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                                {{ __('Select Weapons') }}
                                <span class="text-sm font-normal text-gray-600 ml-2">({{ __('Required:') }} {{ $crimeDetails->required_weapons }})</span>
                            </h3>
                            
                            @if(count($availableWeapons) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($availableWeapons as $weapon)
                                        <label class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-red-50 hover:border-red-300 cursor-pointer transition-all duration-150">
                                            <input type="checkbox" name="gang_weapons[]" value="{{ $weapon->id }}" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                            <div class="ml-3 flex-1">
                                                <p class="font-semibold text-gray-900">{{ $weapon->name }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 italic">{{ __('No available weapons. You may need to purchase weapons for this crime.') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Submit Button --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            {{ __('Send Invitations to Selected Members') }}
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
