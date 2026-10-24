<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Method;

class MethodController extends Controller
{
    public function create()
    {
        return view('components.admin.method', ['data' => method::orderBy('name', 'asc')->paginate(100)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'images' => 'required|file|mimes:jpg,png,webp',
            'code' => 'required',
            'keterangan' => 'required',
            'payment' => 'required',
            'tipe' => 'required',
            'fee_percent' => 'required|numeric',
            'fix_fee' => 'required|numeric',
        ]);

        $file = $request->file('images');
        $folder = 'assets/payment';
        $file->move($folder, $file->getClientOriginalName());    
        $method = new method();
        $method->name = $request->name;
        $method->code = $request->code;
        $method->keterangan = $request->keterangan;
        $method->tipe = $request->tipe;
        $method->payment = $request->payment;
        $method->images = "/".$folder."/".$file->getClientOriginalName();
        $method->fee_percent = $request->fee_percent;
        $method->fix_fee = $request->fix_fee;
        $method->payment_number = $request->payment_number;
        $method->save();

        return back()->with('success', 'Berhasil menambahkan Payment');
    }

    public function delete($id)
    {
        try{
            $data = method::where('id', $id)->select('images')->first();
            unlink(public_path($data->images));
            method::where('id', $id)->delete();
            return back()->with('success', 'Berhasil hapus!');
        }catch(\Exception $e){
            method::where('id', $id)->delete();
            return back()->with('success', 'Berhasil hapus!');
        }
    }

    public function detail($id)
    {
        $data = Method::where('id', $id)->first();
        if (!$data) {
            return back()->withErrors('Metode pembayaran tidak ditemukan');
        }
        $send = "
            <form action='" . route('method.detail.update', [$id]) . "' method='POST' enctype='multipart/form-data'>
                <input type='hidden' name='_token' value='" . csrf_token() . "'>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' value='" . $data->name . "' name='name' required>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Kode</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' value='" . $data->code . "' name='code' required>
                        <p class='text-danger'>pakai code (SALDO) untuk metode saldo</p>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Keterangan</label>
                    <div class='col-lg-10'>
                        <textarea class='form-control' value='" . $data->keterangan . "' name='keterangan' rows='3' required>$data->keterangan</textarea>
                        
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label'>Payment</label>
                    <div class='col-lg-10'>
                        <select class='form-select' name='payment' id='edit_payment' required>
                            <option value='tokopay' " . ($data->payment == 'tokopay' ? 'selected' : '') . ">TOKOPAY.ID</option>
                            <option value='paydisini' " . ($data->payment == 'paydisini' ? 'selected' : '') . ">PAYDISINI.CO.ID</option>
                            <option value='tripay' " . ($data->payment == 'tripay' ? 'selected' : '') . ">TRIPAY.CO.ID</option>
                            <option value='saldo' " . ($data->payment == 'saldo' ? 'selected' : '') . ">SALDO</option>
                            <option value='manual' " . ($data->payment == 'manual' ? 'selected' : '') . ">Manual</option>
                        </select>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label'>Tipe</label>
                    <div class='col-lg-10'>
                        <select class='form-select' name='tipe' id='edit_tipe' required>
                            <option value='SALDO' " . ($data->tipe == 'SALDO' ? 'selected' : '') . ">SALDO ACCOUNT</option>
                            <option value='qris' " . ($data->tipe == 'qris' ? 'selected' : '') . ">QRIS</option>
                            <option value='e-walet' " . ($data->tipe == 'e-walet' ? 'selected' : '') . ">E-Wallet</option>
                            <option value='Bank Transfer' " . ($data->tipe == 'Bank Transfer' ? 'selected' : '') . ">Bank Transfer</option>
                            <option value='DANA' " . ($data->tipe == 'DANA' ? 'selected' : '') . ">DANA</option>
                        </select>
                    </div>
                </div>
                <div class='mb-3 row' id='edit_payment_number_div' style='" . ($data->payment == 'manual' ? 'display: block;' : 'display: none;') . "'>
                    <label class='col-lg-2 col-form-label' id='edit_payment_number_label'>" . ($data->tipe == 'Bank Transfer' ? 'No Rekening' : ($data->tipe == 'DANA' ? 'Nomor DANA' : 'Payment Number')) . "</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' name='payment_number' id='edit_payment_number' value='" . ($data->payment_number ?? '') . "' placeholder='" . ($data->tipe == 'Bank Transfer' ? 'Masukkan no rekening' : ($data->tipe == 'DANA' ? 'Masukkan nomor DANA' : 'Enter payment number')) . "'>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='fix_fee'>Biaya Tetap</label>
                    <div class='col-lg-10'>
                        <input type='number' step='0.01' class='form-control' value='" . $data->fix_fee . "' name='fix_fee' required>
                    </div>
                </div>
            
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='fee_percent'>Biaya Persentase</label>
                    <div class='col-lg-10'>
                        <input type='number' step='0.01' class='form-control' value='" . $data->fee_percent . "' name='fee_percent' required>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Logo</label>
                    <div class='col-lg-10'>
                        <input type='file' class='form-control' name='images'>
                        <img src='" . asset($data->images) . "' alt='Logo' class='mt-3' width='100'>
                    </div>
                </div>
                <div class='modal-footer'>
                   <button type='submit' class='btn btn-primary bg-gradient waves-effect waves-light'>Simpan</button>
                        <button type='button' class='btn btn-dark bg-gradient waves-effect waves-light' data-bs-dismiss='modal'>Close</button>
                </div>
            </form>
            <script>
                $(document).ready(function() {
                    function updateEditLabel() {
                        var tipe = $('#edit_tipe').val();
                        if (tipe === 'Bank Transfer') {
                            $('#edit_payment_number_label').text('No Rekening');
                            $('#edit_payment_number').attr('placeholder', 'Masukkan no rekening');
                        } else if (tipe === 'DANA') {
                            $('#edit_payment_number_label').text('Nomor DANA');
                            $('#edit_payment_number').attr('placeholder', 'Masukkan nomor DANA');
                        }
                    }
                    $('#edit_payment').change(function() {
                        var payment = $(this).val();
                        var tipeSelect = $('#edit_tipe');
                        if (payment === 'manual') {
                            tipeSelect.html('<option value=\"Bank Transfer\">Bank Transfer</option><option value=\"DANA\">DANA</option>');
                            $('#edit_payment_number_div').show();
                            updateEditLabel();
                        } else {
                            tipeSelect.html('<option value=\"SALDO\">Saldo Account</option><option value=\"qris\">QRIS</option><option value=\"e-walet\">E-Wallet</option>');
                            $('#edit_payment_number_div').hide();
                        }
                    });
                    $('#edit_tipe').change(function() {
                        updateEditLabel();
                    });
                    // Initial check
                    if ($('#edit_payment').val() === 'manual') {
                        $('#edit_payment_number_div').show();
                        updateEditLabel();
                    }
                });
            </script>
        ";
    
        return $send;
    }

    
    public function patch(Request $request, $id)
    {
        if($request->file('images')){
            $file = $request->file('images');
            $folder = 'assets/payment';
            $file->move($folder, $file->getClientOriginalName());      
            method::where('id', $id)->update([
                'images' => "/".$folder."/".$file->getClientOriginalName()
            ]);
        }
        
        $method = method::where('id', $id)->update([
            'name' => $request->name,
            'code' => $request->code,
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'payment' => $request->payment,
            'fee_percent' => $request->fee_percent,
            'fix_fee' => $request->fix_fee,
            'payment_number' => $request->payment_number,
        ]);
           
        return back()->with('success', 'Berhasil update payment');        
    }        
}
