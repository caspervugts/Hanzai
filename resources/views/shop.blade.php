<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                              
            </div>
        </div>
        <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    {{ __("Buy weapons") }}                                                     
                </div>  
                    <x-input-error :messages="$errors->get('money')" class="p-6 text-black-900 mt-2" />   
            
                    @if (isset($input['value']))
                        <x-input-label for="test" :value="$input['value']"  />
                    @endif
                <div class="p-6 text-black-900">
                    @foreach ($weapons as $weapon)
                        <p>{{ $weapon->name }}  &nbsp; <a href="/shop/buy/{{ $weapon->id }}/{{ $weapon->value }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  $weapon->value." yen" }}
                        </button></a></p>
                        <p> {{ "- Maximum of ".$weapon->damage." damage "}}</br>
                        @if (!empty($weapon->ammo))
                         {{ "- Uses ".$weapon->ammo." ammunition "}}
                        @endif</p><br/>
                    @endforeach                                                     
                </div>                      
            </div>
        </div>
    </div>
</x-app-layout>
