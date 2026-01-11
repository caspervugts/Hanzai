<x-app-layout>
    @isset($message)
    <div class="alert alert-success">
    <strong>{{@message}}</strong>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leaderboard') }}
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
                <div class="p-6 text-gray-900">       
                <table class="table-auto w-full">
                    <thead>
                        <tr style="text-align:center;">
                            <th class="px-4 py-2">Rank</th>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Money</th>
                            <th class="px-4 py-2">Experience</th>
                            <th class="px-4 py-2">Alive?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr style="text-align:center;">
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">¥{{ number_format($user->money) }}</td>
                            <td class="border px-4 py-2">{{ $user->exp }}</td>
                            <td class="border px-4 py-2" >{{ $user->alive = 1 ? '🩷' : '💀' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>                    
            </div>
        </div>
    
    </div>
</x-app-layout>
