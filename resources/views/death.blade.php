<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-1200 leading-tight text-center" style="color:red;font-size:34px;">
            {{ __('You have been hospitalized.') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Unfortunately, your character has been hospitalized. You are unable to perform any actions for the time being. You have lost all your money, inventory and half of your experience.</p>
</br>
                    <p>You'll be able to continue playing after 24 hours.</p>

                    <p> <br/> Time of death: {{ $user->time_of_death }} </p>
                    @if(!empty($combats) && count($combats) > 0)
                        @php
                            $lastCombat = (is_callable([$combats, 'first'])) ? $combats->first() : $combats[0];
                            $lastEvents = $eventDescriptions[$lastCombat->id] ?? [];
                            $lastEvent = !empty($lastEvents) ? end($lastEvents) : null;
                        @endphp
                        @if($lastEvent)
                            <p><strong>Last combatant:</strong> {{ $lastEvent->move_user_name }}</p>
                        @endif
                    @endif
                </div>
            </div>
    </div>

    <br/>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach($combats as $combat)
                            <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                <p><strong>{{ __("Date:") }}</strong> {{ $combat->battle_starttime }}</p>
                                <div class="p-6 text-gray-900">
                                    <strong>Combat log: </strong>
                                @foreach($HitsEvents as $hitId => $events)
                                    @if($combat->id === $hitId)
                                        <ul>
                                            @foreach($eventDescriptions[$hitId] ?? [] as $event)
                                                <li><b>{{ $event->move_user_name }}</B> {{ $event->event_detail->event_description_part_one }} <b>{{ $event->move_recipient_name }}</b> {{ $event->event_detail->event_description_part_two }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            </div>
                            </div>
                        @endforeach
                </div>
            </div>
    </div>
</x-app-layout>
