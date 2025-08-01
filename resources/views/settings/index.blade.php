@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pengaturan Sistem</h5>
    </div>
    <div class="card-body">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                    <i class="fas fa-store"></i> Profil Toko
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax" type="button" role="tab">
                    <i class="fas fa-percent"></i> Pajak & Diskon
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                    <i class="fas fa-credit-card"></i> Metode Pembayaran
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="settingsTabContent">
            <!-- Profil Toko -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <form method="POST" action="{{ route('settings.updateProfile') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $settings->store_name ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control">{{ old('address', $settings->address ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control">
                        @if(isset($settings->logo))
                            <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="mt-2 rounded" height="50">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>

            <!-- Pajak & Diskon -->
            <div class="tab-pane fade" id="tax" role="tabpanel">
                <form method="POST" action="{{ route('settings.updateTax') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pajak (%)</label>
                        <input type="number" name="tax" class="form-control" value="{{ old('tax', $settings->tax ?? 10) }}" min="0" max="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diskon Default (%)</label>
                        <input type="number" name="discount" class="form-control" value="{{ old('discount', $settings->discount ?? 0) }}" min="0" max="100">
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </form>
            </div>

            <!-- Metode Pembayaran -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <form method="POST" action="{{ route('settings.storePaymentMethod') }}" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Metode</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: QRIS, BCA, OVO" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Akun</label>
                            <input type="text" name="account_number" class="form-control" placeholder="Nomor Rekening / No. E-Wallet">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Pemilik</label>
                            <input type="text" name="account_name" class="form-control" placeholder="Nama Pemilik Akun">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Upload QR Code</label>
                            <input type="file" name="qr_code" class="form-control">
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Metode
                            </button>
                        </div>
                    </div>
                </form>

                <h6>Daftar Metode Pembayaran</h6>
                <table class="table table-bordered table-sm mt-2">
                    <thead class="table-success">
                        <tr>
                            <th>Metode</th>
                            <th>No Akun</th>
                            <th>Nama Pemilik</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->name }}</td>
                            <td>{{ $payment->account_number ?? '-' }}</td>
                            <td>{{ $payment->account_name ?? '-' }}</td>
                            <td>
                                @if($payment->qr_code)
                                    <img src="{{ asset('storage/'.$payment->qr_code) }}" alt="QR" height="50">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('settings.deletePaymentMethod', $payment->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus metode ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada metode pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
