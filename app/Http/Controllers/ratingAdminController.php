<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;

class ratingAdminController extends Controller
{
    public function create()
    {
        $ratings = Rating::select(
                'ratings.bintang', 
                'ratings.comment', 
                'ratings.id',
                'ratings.layanan',
                'ratings.created_at', 
                'pembelians.username', 
                'pembayarans.no_pembeli'
            )
            ->join('pembelians', 'ratings.rating_id', '=', 'pembelians.order_id')
            ->join('pembayarans', 'ratings.rating_id', '=', 'pembayarans.order_id')
            ->orderByDesc('ratings.id')
            ->get();
        
        return view('components.admin.rating-customer', [
            'ratings' => $ratings
        ]);
    }

    public function destroy($id) {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        return redirect()->back()->with('success', 'Rating berhasil Dihapus');
    }
}
