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

    <div class="py-12">
        @if ($success = Session::get('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
            <div class="alert alert-success">
                <p>{{ $success }}</p>
            </div>
                </div>
        </div>
        </div>
        @endif
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
        <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    {{ __("Rob someone") }}  
                                                   
                </div>  
                <div class="p-6 text-gray-900">
                    <a href="/crime/1/1" class="btn btn-warning">  <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $robdata[0]->difficulty.("%") }}
                    </button></a> || {{ $robdata[0]->description }}                                  
                </div>
                <div class="p-6 text-gray-900">
                    <a href="/crime/1/2" class="btn btn-warning">  
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $robdata[1]->difficulty.("%") }}
                    </button>
                    </a> || {{ $robdata[1]->description }}                                  
                </div>   
                <div class="p-6 text-gray-900">
                    <a href="/crime/1/3" class="btn btn-warning">  <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $robdata[2]->difficulty.("%") }}
                    </button></a> || {{ $robdata[2]->description }}                                  
                </div>
                <div class="p-6 text-gray-900">
                    <a href="/crime/1/4" class="btn btn-warning">  
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $robdata[3]->difficulty.("%") }}
                    </button>
                    </a> || {{ $robdata[3]->description }}                                  
                </div>              
            </div>
        </div>
    <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    {{ __("Steal a car") }}  
                                                   
                </div>  
                <div class="p-6 text-gray-900">
                    <a href="/crime/2/1" class="btn btn-warning">  <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $cardata[0]->difficulty.("%") }}
                    </button></a> || {{ $cardata[0]->description }}                                  
                </div>
                <div class="p-6 text-gray-900">
                    <a href="/crime/2/2" class="btn btn-warning">  
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $cardata[1]->difficulty.("%") }}
                    </button>
                    </a> || {{ $cardata[1]->description }}                                  
                </div>   
                <div class="p-6 text-gray-900">
                    <a href="/crime/2/3" class="btn btn-warning">  <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $cardata[2]->difficulty.("%") }}
                    </button></a> || {{ $cardata[2]->description }}                                  
                </div>
                <div class="p-6 text-gray-900">
                    <a href="/crime/2/4" class="btn btn-warning">  
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ $cardata[3]->difficulty.("%") }}
                    </button>
                    </a> || {{ $cardata[3]->description }}                                  
                </div>              
            </div>
        </div>
    </div>

    <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    {{ __("Schedule a hit") }}  
                                                   
                </div>  
                <div class="p-6 text-gray-900">
                              @foreach($users as $user)
                                  <div>
                                      <strong>{{ $user->name }}</strong> <a href="/crime/hit/{{ $user->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Schedule a hit" }}
                        </button></a>
                                  </div>
                                  <br/>
                              @endforeach
            </div>
        </div>
    </div>
    <br/>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    {{ __("Previous Hit Log") }}  
                                                   
                </div>  
                <div class="p-6 text-gray-900">
                    @foreach($previousHitsEvents as $hitId => $events)
                        <div>
                            <strong>Latest combat: </strong>
                            <ul>
                                @foreach($eventDescriptions[$hitId] as $event)
                                    <li>{{ $event->move_user_name }} {{ $event->event_detail->event_description }} </li>
                                @endforeach
                            </ul>
                        </div>
                        <br/>
                    @endforeach
                </div>
            </div>
</x-app-layout>
