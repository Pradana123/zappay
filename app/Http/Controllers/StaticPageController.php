<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class StaticPageController extends Controller
{
    private function sharedData(): array
    {
        return [
            'logoheader' => Berita::where('tipe', 'logoheader')->latest()->first(),
            'logofooter' => Berita::where('tipe', 'logofooter')->latest()->first(),
        ];
    }

    public function caraTopUp()
    {
        return view('template.pages.cara-top-up', $this->sharedData());
    }

    public function promo()
    {
        return view('template.pages.promo', $this->sharedData());
    }

    public function faq()
    {
        return view('template.pages.faq', $this->sharedData());
    }

    public function tentangKami()
    {
        return view('template.pages.tentang-kami', $this->sharedData());
    }

    public function refundPolicy()
    {
        return view('template.pages.refund-policy', $this->sharedData());
    }
}
