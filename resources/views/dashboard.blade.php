<x-app-layout>
    <style>
        path:hover { 
            fill: #fce57e;
        }

        /* Dropdown Button */
        .dropbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        }

        /* The container <div> - needed to position the dropdown content */
        .dropdown {
        position: relative;
        display: inline-block;
        }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {background-color: #ddd;}

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {display: block;}

        /* Change the background color of the dropdown button when the dropdown content is shown */
        .dropdown:hover .dropbtn {background-color: #3e8e41;}
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Welcome back") }} {{ $user->name }} <br/>
                    {{ __("Health") }} {{ $user->health }} <br/>
                    {{ __("Experience") }} {{ $user->exp }} <br/>
                    {{ __("Money") }} ¥{{ number_format($user->money) }} <br/>
                    @if ($user->exp >= 0 and $user->exp < 99)
                        Rank: <b>Nobody</b>
                    @elseif ($user->exp < 250)
                        Rank: <b>Bosozoku</b>           
                    @elseif ($user->exp < 500)
                        Rank: <b>Shatei</b> 
                    @elseif ($user->exp < 1000)
                        Rank: <b>Kyodai</b> 
                    @elseif ($user->exp < 2000)
                        Rank: <b>Shateeigashira</b> 
                    @elseif ($user->exp < 5000)
                        Rank: <b>Wakagashira</b> 
                    @elseif ($user->exp > 5000)
                        Rank: <b>Oyabun</b> 
                    @endif
                    
                </div>                
            </div>
        </div>
        <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <b>{{ __("Inventory") }}  </b><br/>
                    @foreach ($weapons as $weapon)
                        <p>{{ $weapon->name }}  </p>
                    @endforeach      
                </div>                
            </div>
            <br>
        </div>
    </div>
</x-app-layout>
