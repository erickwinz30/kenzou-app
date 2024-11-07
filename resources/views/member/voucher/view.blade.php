@extends('member.layout.main')

@section('container')
<div class="pagetitle d-flex justify-content-between align-items-center">
  <h1>Detail Voucher</h1>
  <a href="{{ route('voucher-index') }}" type="button" class="btn btn-primary"
    style="background-color: #012970; border-color:#012970">Kembali</a>
</div><!-- End Page Title -->

<section class="section mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="mt-3">
          <h2 class="fs-5 fw-bold">{{ $voucher->nama }}</h2>
          <p>{{ $voucher->description }}</p>
        </div>
        <div>
          <p>Berlaku hingga {{ \Carbon\Carbon::parse($voucher->to_date)->format('d M Y') }}</p>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
          <div>
            <h2 class="fs-5 fw-bold">Diskon</h2>
            <p>{{ $voucher->discount * 100 }}%</p>
          </div>
          <div>
            <h2 class="fs-5 fw-bold">Minimal transaksi</h2>
            <p>Rp {{ number_format($voucher->minimum_transaction, 0, ',', '.') }}</p>
          </div>
        </div>
        <div>
          <form action="voucher/claim" method="POST" id="claimForm{{ $voucher->id }}"
            onclick="voucherClaim({{ $voucher->id }})">
            @csrf
            @if (!$ownedVouchers->contains('voucher_id', $voucher->id))
            @if($voucher->point_needed > Auth::guard('member')->user()->redeemable_point)
            <p class="card-text fw-bold p-0 m-0" style="color: #012970">Point belum mencukupi!</p>
            @else
            <button class="btn btn-primary" style="background-color: #012970; border-color:#012970">Redeem</button>
            @endif
            <input type="hidden" value="{{ $voucher->id }}" name='voucher_id'>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection