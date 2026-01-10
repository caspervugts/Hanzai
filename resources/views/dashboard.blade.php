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
                    {{ __("Welcome") }} {{ $user->name }} <br/>
                    {{ __("Health") }} {{ $user->health }} <br/>
                    {{ __("Experience") }} {{ $user->exp }} <br/>
                    {{ __("Money") }} {{ $user->money }} <br/>
                    @if ($user->exp <= 49)
                        Nobody
                    @elseif ($user->exp < 100 and $user->exp > 49)
                        Bosozoku                
                    @endif
                    
                </div>                
            </div>
        </div>
        <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Inventory") }}  <br/>
                    @foreach ($weapons as $weapon)
                        <p>{{ $weapon->name }}  </p>
                    @endforeach      
                </div>                
            </div>
            <br>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- <img src="{{ asset('images/japan.svg') }}" alt="Your SVG"> -->
                    {!! file_get_contents(public_path('images/japan.svg')) !!}
                    <!-- <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>     -->
                    <svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"></svg>
                </div>                
            </div>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">Dropdown</button>
        <div class="dropdown-content">
            <a href="#">Link 1</a>
            <a href="#">Link 2</a>
            <a href="#">Link 3</a>
        </div>
    </div>
    <script>
        document.getElementsByClassName('test').addEventListener('mouseenter', function() {
            this.setAttribute('fill', 'red');
        });

        document.getElementsByClassName('test').addEventListener('mouseleave', function() {
            this.setAttribute('fill', 'blue');
        });
    </script>
</x-app-layout>
