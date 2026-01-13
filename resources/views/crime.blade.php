<x-app-layout>

    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crime') }} - {{ $currentCity->name }} 
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">{{ __('Crime') }} — {{ $currentCity->name }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Choose an action to perform in the city. Higher difficulty yields higher rewards.') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">{{ __('Your current money') }}</div>
                    <div class="text-2xl font-semibold">{{ number_format($user->money ?? 0) }} <span class="text-sm">yen</span></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Row: Rob someone | Steal a car -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6 w-full">
                    <h4 class="font-semibold mb-4">{{ __('Rob someone') }}</h4>

                    <div class="space-y-3">
                        @foreach($robdata as $index => $r)
                            <div class="flex items-start justify-between border rounded p-3">
                                <div>
                                    <div class="font-medium">{{ $r->description }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Difficulty') }}: {{ $r->difficulty }}%</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="/crime/1/{{ $index + 1 }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ __('Attempt') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6 w-full">
                    <h4 class="font-semibold mb-4">{{ __('Steal a car') }}</h4>

                    <div class="space-y-3">
                        @foreach($cardata as $index => $c)
                            <div class="flex items-start justify-between border rounded p-3">
                                <div>
                                    <div class="font-medium">{{ $c->description }}</div>
                                    <div class="text-sm text-gray-500">{{ __('Difficulty') }}: {{ $c->difficulty }}%</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="/crime/2/{{ $index + 1 }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ __('Attempt') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Schedule a hit -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-4">{{ __('Schedule a hit') }}</h4>
                    
                        <ul class="space-y-2">
                            @foreach($users as $u)
                                <li class="flex items-center justify-between border rounded p-3">
                                    <div>
                                        <strong>{{ $u->name }}</strong>
                                        <div class="text-xs text-gray-500">{{ $u->role ?? '' }}</div>
                                    </div>
                                    <a href="/crime/hit/{{ $u->id }}" class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">{{ __('Schedule') }}</a>
                                </li>
                            @endforeach
                        </ul>
                    
                </div>

                <!-- Previous Hit Log -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-4">{{ __('Previous Hit Log') }}</h4>
                    @if(empty($previousHitsEvents))
                        <div class="text-sm text-gray-500">{{ __('No past hit logs.') }}</div>
                    @else
                        <div class="mt-3 text-sm text-gray-700 space-y-3">
                        @forelse($previousHitsEvents as $hitId => $events)
                                              
                            <div>
                                <div class="font-medium">{{ __('Latest combat') }}</div>
                                <ul class="list-disc list-inside ml-4">
                                    @foreach($eventDescriptions[$hitId] ?? [] as $event)                                   
                                        <li><b>{{ $event->move_user_name }}</B> {{ $event->event_detail->event_description_part_one }} <b>{{ $event->move_recipient_name }}</b> {{ $event->event_detail->event_description_part_two }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">{{ __('No recent combat logs.') }}</div>
                        @endforelse
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
