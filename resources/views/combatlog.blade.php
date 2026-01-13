<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All combats tied to this account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($combats->isEmpty())
                        <p>{{ __("You have no combat logs.") }}</p>
                    @else
                        @foreach($combats as $combat)
                            <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                <p><strong>{{ __("Date:") }}</strong> {{ $combat->battle_starttime }}</p>
                                <div class="p-6 text-gray-900">
                                    <strong>Combat log: </strong>
                                @foreach($HitsEvents as $hitId => $events)
                                    
                                        
                                        <ul>
                                            @foreach($eventDescriptions[$hitId] as $event)
                                                @if($combat->id === $hitId)
                                                    <li>{{ $event->move_user_name }} {{ $event->event_detail->event_description }} </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    
                                @endforeach
                            </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>
