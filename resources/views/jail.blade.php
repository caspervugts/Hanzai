<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ __("You are currently in jail.") }}  
                    <br>             
                    <!-- Feedback -->
                    <div id="truncate-feedback" class="mt-2 text-green-600 hidden"></div>
                </div>                
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('truncate-crimes');
    const feedback = document.getElementById('truncate-feedback');

    btn.addEventListener('click', async () => {
        if (!confirm('Weet je zeker dat je alle crimes wilt verwijderen?')) return;

        try {
            const res = await fetch('/crimes/truncate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await res.json();
            if (data.ok) {
                // Optioneel feedback tonen (kan ook weggelaten als je direct reload wilt)
                feedback.textContent = data.message || 'Alle crimes zijn verwijderd!';
                feedback.classList.remove('hidden');

                // 1 seconde wachten en dan pagina herladen
                setTimeout(() => location.reload(), 1000);
            } else {
                feedback.textContent = 'Er is iets misgegaan.';
                feedback.classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            feedback.textContent = 'Er is een fout opgetreden.';
            feedback.classList.remove('hidden');
        }
    });
});
</script>
