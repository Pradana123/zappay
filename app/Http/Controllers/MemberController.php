<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function create()
    {
        return view('components.admin.member', ['users' => User::orderBy('created_at', 'desc')->orderBy('created_at', 'desc')->get()]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Berhasil menghapus pengguna');
    }

    public function store(Request $request)
    {
        $request->validate([
        'nama' => 'required',
        'username' => 'required|min:3|unique:users,username|max:255',
        'email' => 'required|email|unique:users,email', // tambahkan validasi email
        'password' => 'required|min:6|max:255',
        'no_wa' => 'required|numeric|unique:users,no_wa',
        'role' => 'required'
    ], [
        'nama.required' => 'Harap isi kolom nama!',
        'username.required' => 'Harap isi kolom username!',
        'username.min' => 'Panjang username minimal 3 huruf',
        'username.unique' => 'Username telah digunakan',
        'username.max' => 'Panjang username maximal 255 huruf',
        'email.required' => 'Harap isi kolom email!', // tambahkan pesan error email
        'email.email' => 'Format email tidak valid', // tambahkan pesan error email
        'email.unique' => 'Email telah digunakan', // tambahkan pesan error email
        'password' => 'Harap isi kolom password',
        'password.min' => 'Panjang password minimal 6 huruf',
        'password.max' => 'Panjang password maximal 255 huruf',
        'no_wa.required' => 'No Whatsapp harus diisi!',
        'no_wa.numeric' => 'No Whatsapp tidak valid',
        'no_wa.unique' => 'No whatsapp telah digunakan'
    ]);
        
        $no = $request->no_wa;
        
        if($no[0] == 0){
            
            $no = str_replace($no[0],'62',$no);
            
        }


        $user = User::create([
    'name' => $request->nama,
    'username' => $request->username,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'no_wa' => $no,
    'balance' => 0,
    'role' => $request->role,
]);

        return back()->with('success', 'Berhasil menambah pengguna');
    }

    public function send(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'balance' => 'required|numeric|min:1'
        ], [
            'balance.min' => 'Balance minimal 1'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) return back()->with('error', 'Username tidak ditemukan');

        $user->update([
            'balance'  => $user->balance + $request->balance
        ]);

        return back()->with('success', 'Berhasil menambahkan saldo');
    }

    public function show($id)
{
    $data = User::where('id', $id)->first();
    $send = " <form action='" . route("member.detail.update", [$id]) . "' method='POST'> 
                <input type='hidden' name='_token' value='" . csrf_token() . "'>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Nama</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' value='" . $data->name . "' name='name'>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Username</label>
                    <div class='col-lg-10'>
                        <input type='text' class='form-control' value='" . $data->username . "' name='username'>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Email</label>
                    <div class='col-lg-10'>
                        <input type='email' class='form-control' value='" . $data->email . "' name='email'>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>No Whatsapp</label>
                    <div class='col-lg-10'>
                        <input type='number' class='form-control' value='" . $data->no_wa . "' name='no_wa'>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label for='' class='col-lg-2 col-form-label'>Role</label>
                    <div class='col-lg-10'>
                        <select class='form-control' name='role'>
                            <option value='Member'>Member</option>
                            <option value='Platinum'>Platinum</option>
                            <option value='Gold'>Gold</option>
                        </select>
                    </div>
                </div>
                <div class='mb-3 row'>
                    <label class='col-lg-2 col-form-label' for='example-fileinput'>Balance</label>
                    <div class='col-lg-10'>
                        <input type='number' class='form-control' value='" . $data->balance . "' name='balance'>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='submit' class='btn btn-primary bg-gradient waves-effect waves-light'>Simpan</button>
                    <button type='button' class='btn btn-dark bg-gradient waves-effect waves-light' data-bs-dismiss='modal'>Close</button>
                </div>
            </form> ";
    return $send;
}

    public function patch(Request $request)
{
    $request->validate([
        'name' => 'required',
        'username' => 'required',
        'email' => 'required|email', // tambahkan validasi email
        'no_wa' => 'required|numeric',
        'balance' => 'required|numeric|min:0',
        'role' => 'required'
    ]);

    $no = $request->no_wa;
    if($no[0] == 0){
        $no = str_replace($no[0],'62',$no);
    }

    User::where('username', $request->username)->update([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email, // tambahkan field email
        'balance' => $request->balance,
        'no_wa' => $no,
        'role' => $request->role
    ]);

    return back()->with('success', 'Berhasil update user');
}
}
