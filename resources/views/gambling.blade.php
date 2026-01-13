<x-app-layout>
    @if (session('message'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-200 text-green-800 rounded-md px-4 py-3">
                <strong>{{ session('message') }}</strong>
            </div>
        </div>
    @endif
    <br/>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gambling - Horse Racing') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">{{ __('Horse Racing') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Every hour there is a new race. Place your bets and try your luck!') }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">{{ __('Your current money') }}</div>
                    <div class="text-2xl font-semibold">{{ number_format($user->money) }} <span class="text-sm">yen</span></div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-4">{{ __('Available Horses') }}</h4>
                    <div class="space-y-4">
                        @foreach ($horses as $horse)
                            <div class="border rounded p-4">
                                <div class="w-full mb-3">
                                    <img src="{{ asset('images/horses/horse'.$horse->id.'.png') }}" alt="{{ $horse->name }}" class="w-full h-36 object-cover rounded">
                                </div>

                                <table class="w-full text-sm">
                                    <tbody>
                                        <tr>
                                            <td class="align-top">
                                                <div class="font-medium">#{{ $horse->id }} {{ $horse->name }}</div>
                                                <div class="text-sm text-gray-500">{{ __('Odds') }}: {{ $horse->odds*100 ?? '—' }}%</div>
                                            </td>
</tr>   <tr>
                                            <td class="text-right align-top">
                                                <form method="POST" action="/gambling/placebet/{{ $horse->id }}" class="flex items-center justify-end space-x-2">
                                                    @csrf
                                                    <input type="number" name="amount" min="1" placeholder="Amount" value="{{ old('amount') }}" class="w-24 border rounded px-2 py-1">
                                                    <button type="submit" class="px-3 py-1 bg-indigo-600 border rounded hover:bg-red-700">{{ __('Place bet') }}</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class="pt-3">
                                                @if($horse->payoutMultiplier)
                                                    <div class="text-sm text-gray-700">{{ __('Payout multiplier') }}: <strong>x{{ $horse->payoutMultiplier }}</strong></div>
                                                    <div class="text-xs text-gray-500">{{ __('Payout for 100 yen') }}: {{ number_format(100 * $horse->payoutMultiplier) }} yen</div>
                                                @else
                                                    <div class="text-sm text-gray-500">{{ __('Payout: —') }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-4">{{ __('Your Current Bets') }}</h4>
                    @if($bets->isEmpty())
                        <div class="text-sm text-gray-500">{{ __('You have no active bets.') }}</div>
                    @else
                        <ul class="space-y-2">
                            @foreach ($bets as $bet)
                                <li class="border rounded p-3">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium">{{ $bet->horses->name }}</div>
                                            <div class="text-sm text-gray-500">{{ __('Bet') }}: {{ number_format($bet->amount) }} yen</div>
                                        </div>                                        
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-4">{{ __('Recent Results') }}</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        @forelse ($recentlyCompletedRaces as $completedRace)
                            <li class="border rounded p-3">{{ __('Winner') }}: <strong>#{{ $completedRace->winner }} {{ $completedRace->winner_name ?? $completedRace->winner }}</strong> — {{ \Carbon\Carbon::parse($completedRace->closedate)->format('Y-m-d H:i') ?? '' }}</li>
                        @empty
                            <li class="text-gray-500">{{ __('No recent races.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
