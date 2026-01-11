<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('message'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <!-- User Summary -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">{{ __('Welcome back') }}, {{ $user->name }}</h3>
                    <div class="mt-3 text-sm text-gray-600 space-y-2">
                        <div>{{ __('Health') }}: <span class="font-medium">{{ $user->health }}</span></div>
                        <div>{{ __('Experience') }}: <span class="font-medium">{{ $user->exp }}</span></div>
                        <div>{{ __('Money') }}: <span class="font-medium">¥{{ number_format($user->money) }}</span></div>
                        @php
                            $exp = $user->exp ?? 0;
                            if ($exp < 100) { $rank = 'Nobody'; }
                            elseif ($exp < 250) { $rank = 'Bosozoku'; }
                            elseif ($exp < 500) { $rank = 'Shatei'; }
                            elseif ($exp < 1000) { $rank = 'Kyodai'; }
                            elseif ($exp < 2000) { $rank = 'Shateeigashira'; }
                            elseif ($exp < 5000) { $rank = 'Wakagashira'; }
                            else { $rank = 'Oyabun'; }
                        @endphp
                        
                        <div>{{ __('Rank') }}: <span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded"><b>{{ $rank }}</b></span></div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">{{ __('Inventory') }}</h3>
                    <div class="mt-3 text-sm text-gray-600">
                        @if($weapons->isEmpty())
                            <div class="text-sm text-gray-500">{{ __('You have no weapons.') }}</div>
                        @else
                            <ul class="list-disc list-inside">
                                @foreach ($weapons as $weapon)
                                    <li>{{ $weapon->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Previous Hit Log -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold">{{ __('Previous Hit Log') }}</h3>
                    <div class="mt-3 text-sm text-gray-700 space-y-3">
                        @forelse($previousHitsEvents as $hitId => $events)
                            <div>
                                <div class="font-medium">{{ __('Latest combat') }} — {{ $hitId }}</div>
                                <ul class="list-disc list-inside ml-4">
                                    @foreach($eventDescriptions[$hitId] ?? [] as $event)
                                        <li>{{ $event->move_user_name }} {{ $event->event_detail->event_description }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">{{ __('No recent combat logs.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
