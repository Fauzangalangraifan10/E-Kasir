<?php
namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        $payments = PaymentMethod::all();
        return view('settings.index', compact('settings', 'payments'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $settings = Settings::firstOrNew();
        $settings->store_name = $request->store_name;
        $settings->address = $request->address;
        $settings->phone = $request->phone;

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::delete('public/' . $settings->logo);
            }
            $settings->logo = $request->file('logo')->store('logos', 'public');
        }

        $settings->save();
        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }

    public function updateTax(Request $request)
    {
        $request->validate([
            'tax' => 'required|numeric|min:0|max:100',
            'discount' => 'required|numeric|min:0|max:100',
        ]);

        $settings = Settings::firstOrNew();
        $settings->tax = $request->tax;
        $settings->discount = $request->discount;
        $settings->save();

        return back()->with('success', 'Pajak & diskon berhasil diperbarui.');
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'qr_code' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $payment = new PaymentMethod();
        $payment->name = $request->name;
        $payment->account_number = $request->account_number;
        $payment->account_name = $request->account_name;

        if ($request->hasFile('qr_code')) {
            $payment->qr_code = $request->file('qr_code')->store('qrcodes', 'public');
        }

        $payment->save();
        return back()->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function deletePaymentMethod($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        if ($payment->qr_code) {
            Storage::delete('public/' . $payment->qr_code);
        }
        $payment->delete();
        return back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
