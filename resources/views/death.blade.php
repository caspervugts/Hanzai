<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-1200 leading-tight text-center" style="color:red;font-size:34px;">
            {{ __('You have died.') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Unfortunately, your character has died. Don't worry, death is a part of the game! You can create a new character and start your criminal journey anew. Remember to play smart and strategize to avoid untimely demises in the future. Good luck out there!</p>
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
                </div>
            </div>
    </div>
</x-app-layout>
