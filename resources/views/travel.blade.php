<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Travel to a different prefecture') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-black-900">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color: red">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        </div>
        </div>
        @endif
        <br/>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">              
                <div class="p-6 text-gray-900">
                    <B>{{ __("Available Prefectures to Travel") }}   </b>
                              @foreach($prefectures as $prefecture)
                                  <div>
                                      <strong>{{ $prefecture->name }}</strong> <br/> {{ $prefecture->description }} - Tax {{ $prefecture->tax_percentage }}% <br/> 
                                      @if($prefecture->boss_name)
                                          Controlled by: <strong>{{ $prefecture->boss_name }}</strong><br/>
                                      @else
                                          <span class="text-gray-500">Unclaimed</span><br/>
                                      @endif
                                      Travel Cost: ¥{{ number_format($prefecture->travel_cost) }} 
                                      <a href="/prefecture/travel/{{ $prefecture->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Travel" }}
                        </button></a>
                                  </div>
                                  <br/>
                              @endforeach                        
                </div>
</div>
</div>
</x-app-layout>
