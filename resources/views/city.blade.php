<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('City') }} - {{ $currentCity->name }} 
        </h2>
    </x-slot>
<br/>
   <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900">
                    {{ __("All city inhabitants") }}   
                              @foreach($users as $user)
                                  <div>
                                      <strong>{{ $user->name }}</strong> <a href="/city/hit/{{ $user->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Schedule a hit" }}
                        </button></a>
                                  </div>
                                  <br/>
                              @endforeach                     
                </div>               
            </div>
    </div>
</x-app-layout>
