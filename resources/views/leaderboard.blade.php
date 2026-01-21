<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @isset($message)
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <strong>{{ $message }}</strong>
                </div>
            @endisset

            {{-- Leaderboard Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        {{ __('Top Yakuza') }}
                    </h3>

                    {{-- Desktop Table View (hidden on mobile) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-left">
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">{{ __('Rank') }}</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('Player') }}</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('Money') }}</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('Experience') }}</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center w-20">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($users as $index => $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm
                                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $index === 1 ? 'bg-gray-100 text-gray-700' : '' }}
                                                {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $index > 2 ? 'bg-blue-50 text-blue-600' : '' }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            ¥{{ number_format($user->money) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ number_format($user->exp) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-2xl">{{ $user->alive ? '🩷' : '💀' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View (visible only on mobile) --}}
                    <div class="md:hidden space-y-3">
                        @foreach ($users as $index => $user)
                            <div class="border rounded-lg p-4 {{ $user->alive ? 'bg-white' : 'bg-red-50 border-red-200' }} shadow-sm">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold
                                            {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 text-lg' : '' }}
                                            {{ $index === 1 ? 'bg-gray-200 text-gray-700' : '' }}
                                            {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                            {{ $index > 2 ? 'bg-blue-50 text-blue-600' : '' }}">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-lg">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ __('Rank') }} #{{ $index + 1 }}</p>
                                        </div>
                                    </div>
                                    <span class="text-3xl">{{ $user->alive ? '🩷' : '💀' }}</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">{{ __('Money') }}</p>
                                        <p class="font-semibold text-gray-900">¥{{ number_format($user->money) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">{{ __('Experience') }}</p>
                                        <p class="font-semibold text-gray-900">{{ number_format($user->exp) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($users) === 0)
                        <div class="text-center py-12">
                            <p class="text-gray-600 italic">{{ __('No players to display') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
