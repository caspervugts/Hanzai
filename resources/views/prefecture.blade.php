<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prefecture') }} - {{ $currentPrefecture->name }} 
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
                    <B>{{ __("Gun Shop") }}   </b>
                              @foreach($weapons as $weapon)
                                  <div>
                                      <strong>{{ $weapon->name }}</strong> <br/> {{ $weapon->description }} <br/> Costs ¥{{ number_format($weapon->value) }} 
                                      <a href="/shop/buy/{{ $weapon->id }}/{{ $weapon->value }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Buy" }}
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
                <div class="p-6 text-gray-900">
                    <B>{{ __("Konbini") }}   </b>
                              @foreach($foods as $food)
                                  <div>
                                      <strong>{{ $food->name }}</strong> <br/> {{ $food->description }} <br/> Heals {{ $food->health_restore }} HP <br/> Costs ¥{{ number_format($food->value) }} 
                                      <a href="/city/food/buy/{{ $food->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Buy" }}
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
                
                <div class="p-6 text-gray-900">
                    {{ __("All prefecture inhabitants") }}   
                              @foreach($users as $user)
                                  <div>
                                      <strong>{{ $user->name }}</strong>
                                  </div>
                                  <br/>
                              @endforeach                     
                </div>               
            </div>
    </div>
    
    <br/>
    @if ($currentPrefecture->boss_id  == null)
   <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900">
                    {{ __("This prefecture has no boss currently.") }}  <br/>
                    {{ __("Would you like to claim it for ") }} ¥{{ number_format($currentPrefecture->investment_cost) }}? <br/><br/>
                    <a href="/prefecture/claim/{{ $currentPrefecture->id }}">
                        <button class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __("Purchase control for ") }} ¥{{ number_format($currentPrefecture->investment_cost) }}
                        </button>
                    </a>
                                                 
                </div>               
            </div>
    </div>
    @endif
</div>
</x-app-layout>
