<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use App\Models\Kategori;
use Auth;
use Illuminate\Support\Facades\Hash;

class DsController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $username = Auth::user()->username;
        $totalPembelian = Pembelian::where('username', $username)
            ->whereDate('created_at', $today)
            ->sum('harga');
        $banyakPembelian = Pembelian::where('username', $username)
            ->whereDate('created_at', $today)
            ->count();
        $banyakPembelianPending = Pembelian::where('username', $username)
            ->whereDate('created_at', $today)
            ->where('status', 'Pending')
            ->count();
        $banyakPembelianSuccess = Pembelian::where('username', $username)
            ->whereDate('created_at', $today)
            ->where('status', 'Sukses')
            ->count();
        $banyakPembelianBatal = Pembelian::where('username', $username)
            ->whereDate('created_at', $today)
            ->where('status', 'Batal')
            ->count();

        return view('template.dashboard', [
            'data' => \App\Models\Pembelian::where('username', $username)
                ->whereDate('created_at', $today)
                ->get(),
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
            'total_pembelian' => $totalPembelian,
            'banyak_pembelian' => $banyakPembelian,
            'banyak_pembelian_pending' => $banyakPembelianPending,
            'banyak_pembelian_success' => $banyakPembelianSuccess,
            'banyak_pembelian_batal' => $banyakPembelianBatal,
        ]);
    }

    public function editProfile()
    {
        return view('template.profile', [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ]);
    }

    public function saveEditProfile(Request $request)
{
    $request->validate([
    'name' => 'required',
    'no_wa' => 'required|numeric',
    'password' => 'nullable|min:6|max:255',
], [
    'name.required' => 'Harap isi kolom nama!',
    'no_wa.required' => 'Harap isi no whatsapp!',
    'no_wa.numeric' => 'No whatsapp tidak valid!',
    'password.min' => 'Panjang password minimal 6 huruf',
    'password.max' => 'Panjang password maximal 255 huruf',
]);

\Log::info('Validasi berhasil');


    $user = Auth()->user();

    $user->name = $request->name;
    $user->no_wa = $request->no_wa;

    if ($request->password) {
        \Log::info('Password akan diupdate');
        $user->password = Hash::make($request->password);
    } else {
        \Log::info('Password tidak diupdate');
    }

    try {
    if ($user->save()) {
        \Log::info('Data berhasil disimpan');
    } else {
        \Log::info('Data gagal disimpan');
    }
} catch (\Exception $e) {
    \Log::info('Error: ' . $e->getMessage());
}

    return back()->with('success', 'Profil berhasil diubah');
}
}