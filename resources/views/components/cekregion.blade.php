@extends('template.template')

@section('custom_style')
<style>
    .result {
        margin-top: 4px;
        padding: 8px;
        border-radius: 4px;
        display: none;
        text-align: center;
    }
</style>
@endsection

@section('content')

@include('../navbar')

<section class="relative overflow-hidden">
    <div class="mx-auto w-full max-w-xl space-y-8 px-4 pt-10 pb-48">
        <div>
            <a href="/" style="outline:none;">
                <img
                    src="{{ url('') }}{{ !$config ? '' : $config->logo_header }}"
                    width="300"
                    height="300"
                    alt="Logo"
                    class="mx-auto h-32 w-auto"
                    style="color: transparent;"
                />
            </a>
            <h2 class="mt-2 text-center text-3xl font-bold tracking-tight text-white">Cek Username & Region Mobile Legends</h2>
            <p class="mt-2 text-center text-sm text-white">
                Masukkan ID dan Server untuk mendapatkan informasi username dan region.
            </p>
        </div>

        <form class="space-y-6" id="stalkForm">
            <div class="flex flex-col gap-y-2">
                <label class="block text-xs font-medium text-white">ID</label>
                <input
                    placeholder="Masukkan ID"
                    type="text"
                    id="user_id"
                    name="user_id"
                    class="w-full rounded-md border bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:outline-none focus:bg-white"
                />
            </div>

            <div class="flex flex-col gap-y-2">
                <label class="block text-xs font-medium text-white">Server</label>
                <input
                    placeholder="Masukkan Server"
                    type="text"
                    id="zone_id"
                    name="zone_id"
                    class="w-full rounded-md border bg-murky-200 px-3 py-2 text-xs text-murky-800 placeholder-murky-800 focus:outline-none focus:bg-white"
                />
            </div>

            <div class="flex items-center gap-x-4">
                <button
                    type="submit"
                    class="inline-flex neverzoom items-center justify-center rounded-md bg-primary-500 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-400 disabled:cursor-not-allowed disabled:opacity-75 group relative flex w-full"
                >
                    Cek Username Dan Region
                </button>
                <a
                    class="inline-flex neverzoom items-center justify-center rounded-md bg-primary-500 px-4 py-2 text-sm font-medium text-white duration-300 hover:bg-primary-400 group relative flex w-full outline-none"
                    href="/id/mobile-legends"
                >
                    Top UP Mobile Legends
                </a>
            </div>
        </form>

        <!-- Hasil akan ditampilkan di sini -->
        <div
            id="result"
            class="result rounded-md border border-transparent bg-murky-700 p-4 text-center text-sm font-semibold ring-2 ring-primary-500"
        >
            <p><strong>Nickname:</strong> <span id="resultNick"></span></p>
            <p><strong>Region:</strong> <span id="resultRegion" class="uppercase"></span></p>
            <p><strong>Author:</strong> <span id="resultAuthor"></span></p>
        </div>
    </div>
</section>

@include('../footer')

@push('custom_script')
<script>
document.getElementById('stalkForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    
    const userId = document.getElementById('user_id').value.trim();
    const zoneId = document.getElementById('zone_id').value.trim();
    const result = document.getElementById('result');
    const rNick = document.getElementById('resultNick');
    const rRegion = document.getElementById('resultRegion');
    const rAuthor = document.getElementById('resultAuthor');
    const button = document.querySelector('button[type="submit"]');

    if (!userId || !zoneId) {
        alert('ID dan Server harus diisi!');
        return;
    }

    button.disabled = true;
    button.textContent = "Sedang mencari...";

    try {
        const res = await fetch("{{ url('/id/check-mlbb') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                uid: parseInt(userId),
                server: parseInt(zoneId)
            })
        });

        if (!res.ok) throw new Error("Gagal menghubungi server.");

        const data = await res.json();

        if (data.status) {
            rNick.textContent = data.data.nickname; // Ganti jadi nickname
                rAuthor.textContent = data.data.author;
            rRegion.textContent = data.data.region;
            result.style.display = 'block';
        } else {
            alert('Data tidak ditemukan.');
        }
        } catch (err) {
            console.error('Error:', err);
            alert('Terjadi kesalahan saat mengambil data.');
        } finally {
            button.disabled = false;
            button.textContent = "Cek Username Dan Region";
        }
    });
</script>
@endpush

@endsection
