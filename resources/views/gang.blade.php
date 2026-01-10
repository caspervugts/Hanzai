<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gangs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if (isset($input['value']))
                        <x-input-label for="test" :value="$input['value']"  />
                    @endif   
                    
                    <div class="p-6 text-black-900">
                        <b>  {{ __("Your gang information") }}                                                     </b> 
</div>
                <div class="p-6 text-black-900">
                    
                <p>{{ __("Gang name: ") }} {{ $gangs[0]->name }}  </p>
                <p>{{ __("Gang boss ID: ") }} {{ $isBoss[0]->name }}  </p>
                <p>{{ __("Description: ") }} {{ $gangs[0]->description }}  </p>
                <p>{{ __("Gang money: ") }} {{ $gangs[0]->gang_money }} yen </p>
                <p>{{ __("Total gang experience: ") }} {{ $gangs[0]->total_gang_exp }} </p>
                
                </div>
                <div class="p-6 text-black-900">
                    <b>{{ __("Gang members:") }}</b> <br/>
                    @foreach ($gangMembers as $member)
                        <p>{{ $member->name }} - {{ __("Health: ") }} {{ $member->health }} - {{ __("Experience: ") }} {{ $member->exp }} - {{ __("Money: ") }} {{ $member->money }} yen </p>
                    @endforeach
                
                </div>

                <div class="p-6 text-black-900">                    
                    <p>{{ "Leave your current gang" }}  &nbsp; <a href="/gang/leave/{{ $gangs[0]->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{  "Leave your gang" }}
                    </button></a></p>                                                  
                </div>              
            </div>
        </div>
        <br/>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if (isset($input['value']))
                        <x-input-label for="test" :value="$input['value']"  />
                    @endif   
                    
                    @if (isset($userBoss) and $userBoss == true)
                    <div class="p-6 text-black-900">
                        <b>  {{ __("Gang approval requests") }}                                                     </b>  
                        @if (isset($approvalRequests))
                            @foreach ($approvalRequests as $request)
                                <p>{{ __("User ID: ") }} {{ $request->name }} &nbsp; <a href="/gang/approve/{{ $request->user_id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{  "Approve" }}
                                </button></a>
                                <a href="/gang/reject/{{ $request->user_id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{  "Reject" }}
                                </button></a>
                            </p>
                            @endforeach
                        @else
                            <p>{{ __("No pending requests") }}</p>
                        @endif   
                    @endif
            </div>
        </div>
        <br/>
    </div>
</x-app-layout>
