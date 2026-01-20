<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gang Overview') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            {{-- Error Message --}}
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong>{{ session('error') }}</strong>
                </div>
            @endif

            @if (session($input = 'value'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
                    <strong>{{ $input['value'] }}</strong>
                </div>
            @endif

            {{-- Gang Information Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                        {{ __("Your Gang Information") }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">{{ __("Gang Name") }}</p>
                            <p class="font-semibold text-gray-900">{{ $gangs[0]->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __("Gang Boss") }}</p>
                            <p class="font-semibold text-gray-900">{{ $isBoss[0]->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __("Gang Money") }}</p>
                            <p class="font-semibold text-gray-900">¥{{ number_format($gangs[0]->gang_money) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __("Total Experience") }}</p>
                            <p class="font-semibold text-gray-900">{{ number_format($gangs[0]->total_gang_exp) }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-1">{{ __("Description") }}</p>
                        <p class="text-gray-800">{{ $gangs[0]->description }}</p>
                    </div>                   
   
                    <div class="pt-4 border-t">
                        <form method="POST" action="/gang/deposit/{{ $gangs[0]->id }}" class="flex items-left justify-end space-x-2">
                            @csrf
                            <input type="number" name="amount" min="1" placeholder="Amount" value="{{ old('amount') }}" class="w-24 border rounded px-2 py-1">
                            <button type="submit" class="px-3 py-1 bg-indigo-600 border rounded hover:bg-red-700">{{ __('Deposit') }}</button>
                        </form>

                        <a href="/gang/leave/{{ $gangs[0]->id }}">
                            <button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __("Leave Gang") }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Gang Members Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                        {{ __("Gang Members") }} ({{ count($gangMembers) }})
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach ($gangMembers as $member)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $member->name }}</p>
                                </div>
                                <div class="flex gap-4 text-sm text-gray-600">
                                    <span>{{ __("HP") }}: <strong>{{ $member->health }}</strong></span>
                                    <span>{{ __("EXP") }}: <strong>{{ number_format($member->exp) }}</strong></span>
                                    <span>{{ __("Money") }}: <strong>¥{{ number_format($member->money) }}</strong></span>
                                </div>
                                
                                <a href="/gang/bail/{{ $member->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{  "Post bail for 20,000¥" }}
                                </button></a>
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Open Invitations Card --}}
            @if(isset($openInvite) && count($openInvite) > 0)
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-400">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-yellow-300 pb-2">
                            {{ __("Open Crew Robbery Invitations") }}
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach ($openInvite as $invitation)
                                <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-yellow-200">
                                    <p class="font-semibold text-gray-900">{{ __("Crew Robbery Invitation") }}</p>
                                    <div class="flex gap-2">
                                        <a href="/gang/crime/approve/{{ $invitation->gang_crime_id }}">
                                            <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __("Accept") }}
                                            </button>
                                        </a>
                                        <a href="/gang/crime/reject/{{ $invitation->gang_crime_id }}">
                                            <button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __("Reject") }}
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Gang Crimes Card --}}
            @if(isset($recentGangCrimes) && count($recentGangCrimes) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                            {{ __("Recent Gang Robberies") }}
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach ($recentGangCrimes as $crime)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="font-bold text-gray-900 mb-2">{{ $crime->name }}</p>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><span class="font-semibold">{{ __("Performed by") }}:</span> {{ $crime->user_id }}</p>
                                        <p><span class="font-semibold">{{ __("Result") }}:</span> {{ $crime->result }}</p>
                                        <p><span class="font-semibold">{{ __("Date") }}:</span> {{ $crime->createdate }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Available Gang Crimes Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                        {{ __("Plan a Robbery with Fellow Gang Members") }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($gangCrimes as $crime)
                            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                                <p class="font-bold text-gray-900 text-lg mb-3">{{ $crime->name }}</p>
                                
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <p>
                                        <span class="font-semibold">{{ __("Reward") }}:</span> 
                                        ¥{{ number_format($crime->min_money) }} - ¥{{ number_format($crime->max_money) }}
                                    </p>
                                    <p>
                                        <span class="font-semibold">{{ __("Requirements") }}:</span>
                                    </p>
                                    <ul class="ml-4 space-y-1">
                                        <li>• {{ __("Crew Size") }}: {{ $crime->required_gang_size }} members</li>
                                        <li>• {{ __("Money") }}: ¥{{ number_format($crime->required_money) }}</li>
                                        <li>• {{ __("Weapons") }}: {{ $crime->required_weapons }}</li>
                                        <li>• {{ __("Cars") }}: {{ $crime->required_cars }}</li>
                                    </ul>
                                </div>
                                
                                <a href="/gang/crime/start/{{ $crime->id }}">
                                    <button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __("Start Crime") }}
                                    </button>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Gang Approval Requests (Boss Only) --}}
            @if (isset($userBoss) && $userBoss == true)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">
                            {{ __("Gang Approval Requests") }}
                        </h3>
                        
                        @if (isset($approvalRequests) && count($approvalRequests) > 0)
                            <div class="space-y-3">
                                @foreach ($approvalRequests as $request)
                                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $request->name }}</p>
                                            <p class="text-sm text-gray-600">{{ __("Wants to join your gang") }}</p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="/gang/approve/{{ $request->user_id }}">
                                                <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __("Approve") }}
                                                </button>
                                            </a>
                                            <a href="/gang/reject/{{ $request->user_id }}">
                                                <button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __("Reject") }}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 italic">{{ __("No pending requests") }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
