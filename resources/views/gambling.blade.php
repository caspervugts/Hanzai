<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gambling') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if (isset($input['value']))
                        <x-input-label for="test" :value="$input['value']"  />
                    @endif        
            </div>
        </div>
        <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                <b>  {{ __("Horse racing") }}                                                     </b>
                </div>  
                <div class="p-6 text-black-900">
                    <div>
                        {{ __("Your current money: ") }} {{ $user->money }} yen
                    </div>

                    <div>
                        {{ __("Place your bets on the horses below:") }}
                    </div>                    
                </div>
               @if ($errors->any())
               <div class="p-6 text-black-900">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color: red">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                
                </div>
                @endif
                <div class="p-6 text-black-900 flex">
                    <div class="w-1/2 pl-2">
                        @foreach ($horses as $horse)
                            <p><b>{{ $horse->id }} - {{ $horse->name }}  </b><br/> 
                        <form method="POST" action="/gambling/placebet/{{ $horse->id }}">
                            @csrf

                            <label>
                                Amount:
                                <input type="number" name="amount">
                            </label>
                            <button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" type="submit">Place bet</button>
                        </form>
                            <br> <br>
                            
                        </p>
                        @endforeach                                                 
                    </div>                      

                    <div class="w-1/2 pl-2" style="margin-left: 50px;">
                        <b>{{ __("Your current bets:") }}</b> <br/>
                        @foreach ($bets as $bet)
                            <p>
                                {{ "You have placed a bet of ".$bet->amount." yen on ".$bet->horses->name }}
                            </p>
                        @endforeach                                                 
                    </div>    
                    <div class="w-1/2 pl-2" style="margin-left: 50px;">
                        <b>{{ __("Previous results:") }}</b> <br/>
                        @foreach ($recentlyCompletedRaces as $completedRace)
                            <p>
                                {{ "The winning horse was ".$completedRace->winner }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
