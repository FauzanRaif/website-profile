@extends('layout.app')
@section('title', 'Dashboard Monitoring')
@section('content')
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border"><p class="text-xs text-gray-500 uppercase">Kekeruhan</p><h3 class="text-2xl font-bold mt-2">{{ number_format($latest->kekeruhan, 0) }} NTU</h3></div>
        <div class="bg-white p-6 rounded-xl shadow-sm border"><p class="text-xs text-gray-500 uppercase">Sisa Pakan</p><h3 class="text-2xl font-bold mt-2">{{ number_format($latest->sisa_pakan, 0) }}%</h3></div>
    </div>

    <div class="grid grid-cols-3 gap-8">
        <div class="col-span-2 bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-bold mb-4">Grafik Kualitas Air</h3>
            <div class="h-64"><canvas id="chart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="font-bold mb-4">Kontrol Pakan</h3>
            <input type="range" id="gram" min="10" max="100" value="30" class="w-full mb-2">
            <p class="text-right font-bold text-brand-600 mb-4" id="val">30g</p>
            <button onclick="feed()" id="btn" class="w-full bg-brand-600 text-white py-3 rounded-lg hover:bg-brand-700">Beri Pakan</button>
        </div>
    </div>

    <script>
        document.getElementById('gram').oninput = function(){document.getElementById('val').innerText = this.value+'g'}
        
        function feed() {
            let btn = document.getElementById('btn');
            btn.innerHTML = 'Mengirim...'; btn.disabled = true;
            
            fetch("{{ route('trigger.feed') }}", {
                method: "POST", headers: {"Content-Type":"application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}"},
                body: JSON.stringify({gram: document.getElementById('gram').value})
            }).then(()=>{ alert('Perintah Dikirim!'); window.location.reload(); });
        }

        new Chart(document.getElementById('chart'), {
            type: 'line',
            data: {
                labels: ['10:00','11:00','12:00','13:00','14:00'],
                datasets: [{label:'pH', data:[7,7.2,7.1,7.3,7], borderColor:'#3b82f6', tension:0.4}]
            }, options:{maintainAspectRatio:false}
        });
        
        // Auto Refresh
        setTimeout(() => { window.location.reload() }, 5000);
    </script>
@endsection