<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Str;
use App\Services\PricingGuard;

class LayananController extends Controller
{
    public function create()
    {
        $datas = Layanan::join('kategoris', 'layanans.kategori_id', 'kategoris.id')->orderBy('created_at', 'desc')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori')->get();

        $kategori = Kategori::get();

        return view('components.admin.layanan', ['datas' => $datas, 'kategoris' => $kategori]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric|min:0',
            'harga_member' => 'required|numeric|min:0',
            'harga_platinum' => 'required|numeric|min:0',
            'harga_gold' => 'required|numeric|min:0',
            'profit' => 'required|numeric|min:0',
            'profit_member' => 'required|numeric|min:0',
            'profit_platinum' => 'required|numeric|min:0',
            'profit_gold' => 'required|numeric|min:0',
            'product_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'banner_flash_sale' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'provider_id' => 'required|unique:layanans,provider_id',
            'provider' => 'required',
        ]);
        
        if ($request->file('product_logo')){
        
            $img = $request->file('product_logo');
            $filename = Str::random('15') . '.' . $img->extension();
            $img->move('assets/product_logo', $filename);
        
        }
        
         if($request->file('banner_flash_sale')){
            $imgfs = $request->file('banner_flash_sale');
            $filenamefs = Str::random('15') . '.' . $imgfs->extension();
            $imgfs->move('assets/banner_flash_sale', $filenamefs);
        }

        $pricingGuard = app(PricingGuard::class);

        $layanan = new Layanan();
        $layanan->kategori_id = $request->kategori;
        $layanan->layanan = $request->nama;
        $layanan->provider_id = $request->provider_id;
        $layanan->harga = $pricingGuard->applyMarkup($request->harga, $request->profit);
        $layanan->harga_member = $pricingGuard->applyMarkup($request->harga_member, $request->profit_member);
        $layanan->harga_platinum = $pricingGuard->applyMarkup($request->harga_platinum, $request->profit_platinum);
        $layanan->harga_gold = $pricingGuard->applyMarkup($request->harga_gold, $request->profit_gold);
        $layanan->profit = $request->profit;
        $layanan->profit_member = $request->profit_member;
        $layanan->profit_platinum = $request->profit_platinum;
        $layanan->profit_gold = $request->profit_gold;
        $layanan->provider = $request->provider;
        $layanan->catatan = '';
        $layanan->status = 'available';
        $layanan->product_logo = ($request->file('product_logo') ? '/assets/product_logo/'.$filename : '');
        $layanan->harga_flash_sale = $request->harga_flash_sale;
        $layanan->stock_flash_sale = $request->stock_flash_sale;
        $layanan->is_flash_sale = $request->flash_sale;
        $layanan->judul_flash_sale = $request->judul_flash_sale;
        $layanan->banner_flash_sale = ($request->file('banner_flash_sale') ?  '/assets/banner_flash_sale/'.$filenamefs : '');
        $layanan->expired_flash_sale = date('Y-m-d H:i:s', strtotime($request->expired_flash_sale));
        $layanan->save();

        return back()->with('success', 'Berhasil menginput layanan');
    }

    public function delete($id)
    {
        Layanan::where('id', $id)->delete();
        return back()->with('success', 'Berhasil menghapus layanan');
    }

    public function update($id, $status)
    {
        Layanan::where('id', $id)->update([
            'status' => $status
        ]);
        return back()->with('success', 'Berhasil mengupdate layanan');
    }
    
    public function detail($id)
    {
        $data = Layanan::where('id', $id)->first();
        
        $send = "
                <form action='".route("layanan.detail.update", [$id])."' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' name='_token' value='".csrf_token()."'>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Layanan</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='".$data->layanan. "' name='layanan'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Provider</label>
                        <div class='col-lg-10'>
                            <select class='form-select' name='provider'>
                                <option value='digiflazz' " . ($data->provider == 'digiflazz' ? 'selected' : '') . ">Digiflazz</option>
                                <option value='vip' " . ($data->provider == 'vip' ? 'selected' : '' ) . ">Vip Reseller</option>
                                <option value='manual' " . ($data->provider == 'manual' ? 'selected' : '') . ">Manual</option>

                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Provider Id</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='" . $data->provider_id . "' name='provider_id'>
                        </div>
                    </div>  
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='". $data->harga ."' name='harga'>
                        </div>
                    </div>  
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga Member</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='". $data->harga_member ."' name='harga_member'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga Platinum</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='". $data->harga_platinum ."' name='harga_platinum'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga Gold</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='". $data->harga_gold ."' name='harga_gold'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Profit Public (%)</label>
                        <div class='col-lg-10'>
                            <input type='number' step='0.01' min='0' class='form-control' value='". $data->profit ."' name='profit'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Profit Member (%)</label>
                        <div class='col-lg-10'>
                            <input type='number' step='0.01' min='0' class='form-control' value='". $data->profit_member ."' name='profit_member'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Profit Platinum (%)</label>
                        <div class='col-lg-10'>
                            <input type='number' step='0.01' min='0' class='form-control' value='". $data->profit_platinum ."' name='profit_platinum'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Profit Gold (%)</label>
                        <div class='col-lg-10'>
                            <input type='number' step='0.01' min='0' class='form-control' value='". $data->profit_gold ."' name='profit_gold'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label'>Flash Sale?</label>
                        <div class='col-lg-10'>
                            <select class='form-select' name='flash_sale'>
                                <option value='0'>No</option>
                                <option value='1'>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Judul Flash Sale</label>
                        <div class='col-lg-10'>
                            <input type='text' class='form-control' value='". $data->judul_flash_sale ."' name='judul_flash_sale'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Harga Flash Sale</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='". $data->harga_flash_sale ."' name='harga_flash_sale'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Stock Flash Sale</label>
                        <div class='col-lg-10'>
                            <input type='number' class='form-control' value='0' name='stock_flash_sale'>
                        </div>
                    </div>
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Flashsale Berakhir Sampai</label>
                        <div class='col-lg-10'>
                            <input type='datetime-local' class='form-control' value='". $data->expired_flash_sale ."' name='expired_flash_sale' data-provider='flatpickr' data-date-format='d.m.y'
                                data-enable-time>
                        </div>
                    </div>
                   
                    <div class='mb-3 row'>
                        <label class='col-lg-2 col-form-label' for='example-fileinput'>Status</label>
                        <div class='col-lg-10'>
                            <select class='form-control' name='status'>
                                <option value='available'>Available</option>
                                <option value='unavailable'>Unavailable</option>
                            </select>
                        </div>
                    </div>                                        
                    <div class='modal-footer'>
                        <button type='submit' class='btn btn-primary bg-gradient waves-effect waves-light'>Simpan</button>
                        <button type='button' class='btn btn-dark bg-gradient waves-effect waves-light' data-bs-dismiss='modal'>Close</button>
                        
                    </div>
                </form>
        ";

        return $send;        
    }    
        
    
    public function patch(Request $request, $id)
    {
        
        if($request->file('banner_flash_sale')){
            $imgfs = $request->file('banner_flash_sale');
            $filenamefs = Str::random('15') . '.' . $imgfs->extension();
            $imgfs->move('assets/banner_flash_sale', $filenamefs);
        }
        
          if($request->file('product_logo')){
            $img = $request->file('product_logo');
            $filename = Str::random('15') . '.' . $img->extension();
            $img->move('assets/product_logo', $filename);
        }
        
        $request->validate([
            'layanan' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'provider_id' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'harga_member' => 'required|numeric|min:0',
            'harga_platinum' => 'required|numeric|min:0',
            'harga_gold' => 'required|numeric|min:0',
            'profit' => 'nullable|numeric|min:0',
            'profit_member' => 'nullable|numeric|min:0',
            'profit_platinum' => 'nullable|numeric|min:0',
            'profit_gold' => 'nullable|numeric|min:0',
            'product_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'banner_flash_sale' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:4096',
        ]);

        $cek = Layanan::where('id', $id)->firstOrFail();
        $pricingGuard = app(PricingGuard::class);
        $profit = $request->profit ?? $cek->profit;
        $profitMember = $request->profit_member ?? $cek->profit_member;
        $profitPlatinum = $request->profit_platinum ?? $cek->profit_platinum;
        $profitGold = $request->profit_gold ?? $cek->profit_gold;

        
        $layanan = Layanan::where('id', $id)->update([
            'layanan' => $request->layanan,
            'provider' => $request->provider,
            'provider_id' => $request->provider_id,
            'harga' => $pricingGuard->applyMarkup($request->harga, $profit),
            'harga_member' => $pricingGuard->applyMarkup($request->harga_member, $profitMember),
            'harga_platinum' => $pricingGuard->applyMarkup($request->harga_platinum, $profitPlatinum),
            'harga_gold' => $pricingGuard->applyMarkup($request->harga_gold, $profitGold),
            'profit' => $profit,
            'profit_member' => $profitMember,
            'profit_platinum' => $profitPlatinum,
            'profit_gold' => $profitGold,
            'status' => $request->status,
            'harga_flash_sale' => $request->harga_flash_sale,
            'stock_flash_sale' => $request->stock_flash_sale,
            'is_flash_sale' => $request->flash_sale,
            'judul_flash_sale' => $request->judul_flash_sale,
            'expired_flash_sale' => date('Y-m-d H:i:s', strtotime($request->expired_flash_sale)),
            'banner_flash_sale' => (!$request->file('banner_flash_sale') ? $cek->banner_flash_sale : '/assets/banner_flash_sale/'.$filenamefs),
            'product_logo' =>  (!$request->file('product_logo') ? $cek->product_logo : '/assets/product_logo/'.$filename)
        ]);
        
           
        return back()->with('success', 'Berhasil update layanan');        
    }
    
    public function replaceData(Request $request)
{
  $kataReplace = explode(',', $request->input('kata_replace'));
  $kataReplace = array_map('trim', $kataReplace);

  $layanan = Layanan::all();
  foreach ($layanan as $item) {
    $item->layanan = trim(str_ireplace($kataReplace, "", $item->layanan));
    $item->save();
  }

  return back()->with('success', 'Data berhasil diupdate');
}
}
