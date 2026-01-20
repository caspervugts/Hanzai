<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find a gang') }}
        </h2>
    </x-slot>
    @if ($user->exp < 250)
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
            {{ __('You need at least 250 experience to join a gang.') }}
        </div>
    @else
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                              
            </div>
        </div>
        <br/>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black-900">
                    <b>{{ __("Apply for a gang") }}   </b>                                                 
                </div>  
                @if (isset($input['value']))
                        <x-input-label for="test" :value="$input['value']"  />
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

                <div class="p-6 text-black-900">
                    @foreach ($gangs as $gang)
                        <p>{{ $gang->name }}  &nbsp; <a href="/gang/apply/{{ $gang->id }}"><button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{  "Apply" }}
                        </button></a></p>
                        <p>@if (!empty($gang->description))
                         {{ "- Description: ".$gang->description }}
                        @endif</p><br/>
                    @endforeach                                                     
                </div>    
            </div>
            
            <br/>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-black-900">
                    
                    <p><b>{{ "Or create your own gang" }}</b>  &nbsp; 
                        
                    <form method="POST" action="/gang/create">
                        @csrf

                        <label>
                            Name:<br/>
                            <input type="text" name="name">
                        </label>
                        <br/>
                        <label>
                            Description:<br/>
                            <input type="text" name="description">
                        </label><br/><br/>  
                        <button class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" type="submit">Create gang</button>
                    </form>

                </p>
                                                                   
                </div> 
            </div>
        </div>        
    </div>
    @endif
</x-app-layout>
                    
