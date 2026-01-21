<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Combat History') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($combats->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __("No Combat History") }}</h3>
                        <p class="text-gray-600">{{ __("You haven't been in any battles yet.") }}</p>
                    </div>
                </div>
            @else
                {{-- Combat Logs List --}}
                <div class="space-y-4">
                    @foreach($combats as $combat)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500 hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                {{-- Combat Header --}}
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-900">{{ __("Combat Encounter") }}</h3>
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($combat->battle_starttime)->format('M d, Y - H:i:s') }}
                                    </div>
                                </div>

                                {{-- Combat Events --}}
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ __("Battle Log") }}
                                    </h4>
                                    
                                    @if(isset($HitsEvents) && isset($eventDescriptions))
                                        @php
                                            $hasCombatEvents = false;
                                        @endphp
                                        
                                        @foreach($HitsEvents as $hitId => $events)
                                            @if($combat->id === $hitId && isset($eventDescriptions[$hitId]))
                                                @php
                                                    $hasCombatEvents = true;
                                                @endphp
                                                <div class="space-y-2">
                                                    @foreach($eventDescriptions[$hitId] as $index => $event)
                                                        <div class="flex items-start gap-3 p-3 bg-white rounded border border-gray-200 hover:border-red-300 transition-colors duration-150">
                                                            <div class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xs font-bold">
                                                                {{ $index + 1 }}
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="text-gray-800">
                                                                    <span class="font-semibold text-red-700">{{ $event->move_user_name }}</span>
                                                                    <li><b>{{ $event->move_user_name }}</B> {{ $event->event_detail->event_description_part_one }} <b>{{ $event->move_recipient_name }}</b> {{ $event->event_detail->event_description_part_two }}</li>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                        
                                        @if(!$hasCombatEvents)
                                            <p class="text-gray-600 italic text-sm">{{ __("No detailed combat events recorded.") }}</p>
                                        @endif
                                    @else
                                        <p class="text-gray-600 italic text-sm">{{ __("Combat details unavailable.") }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>
