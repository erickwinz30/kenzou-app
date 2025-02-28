@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Voucher</h1>
    <h5 class="card-text fw-bold">{{ auth()->guard('member')->user()->redeemable_point }} RP</h5>
  </div><!-- End Page Title -->

  <section class="section mt-4">
    <div class="col-12">
      <h2 class="fs-5 fw-bold">Voucher yang dimiliki: </h2>
      @if ($ownedVouchers->isEmpty())
        <p class="text-center">Belum memiliki voucher</p>
      @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
          @foreach ($ownedVouchers as $ownedVoucher)
            <div class="col">
              <a href="/voucher/{{ $ownedVoucher->voucher->id }}" class="text-decoration-none" style="color: black">
                <div class="card shadow" style="border-radius: 15px">
                  @php
                    $formattedDate = \Carbon\Carbon::parse($ownedVoucher->voucher->to_date)->translatedFormat(
                        'j F Y, H:i',
                    );
                  @endphp
                  <div class="card-title p-3 m-0">{{ $ownedVoucher->voucher->nama }}</div>
                  <div class="card-text px-3 m-0">{{ $ownedVoucher->voucher->description }}</div>
                  <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="card-text w-50">Berlaku hingga: <strong
                        style="color: #012970">{{ $formattedDate }}</strong>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="col-12 pb-5">
      <h2 class="fs-5 fw-bold">Semua Voucher</h2>
      @if ($vouchers->isEmpty())
        <p class="text-center">Belum ada voucher yang tersedia</p>
      @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
          @foreach ($vouchers as $voucher)
            <div class="col">
              <a href="/voucher/{{ $voucher->id }}" class="text-decoration-none" style="color: black">
                <div class="card shadow" style="border-radius: 15px">
                  @php
                    $formattedDate = \Carbon\Carbon::parse($voucher->to_date)->translatedFormat('j F Y, H:i');
                  @endphp
                  <div class="card-title p-3 m-0">
                    {{ $voucher->nama }}
                    @if ($ownedVouchers->contains('voucher_id', $voucher->id))
                      <span style="color: green;">(telah dimiliki)</span>
                    @endif
                  </div>
                  <div class="card-text px-3 m-0">{{ $voucher->description }}</div>
                  <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="card-text w-50">Berlaku hingga: <strong
                        style="color: #012970">{{ $formattedDate }}</strong>
                    </div>
                    <div class="card-text w-50 text-end"><strong style="color: #012970">{{ $voucher->point_needed }}
                        Point</strong></div>
                  </div>
                  <div class="px-3 pb-3">
                    <form action="/voucher/claim" method="POST" id="claimForm{{ $voucher->id }}"
                      onclick="voucherClaim({{ $voucher->id }})">
                      @csrf
                      @if (!$ownedVouchers->contains('voucher_id', $voucher->id))
                        @if ($voucher->point_needed > Auth::guard('member')->user()->redeemable_point)
                          <p class="card-text fw-bold p-0 m-0" style="color: #012970">Point belum mencukupi!</p>
                        @else
                          <button class="btn btn-primary"
                            style="background-color: #012970; border-color:#012970">Redeem</button>
                        @endif
                        <input type="hidden" value="{{ $voucher->id }}" name='voucher_id'>
                      @endif
                    </form>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  <script>
    function voucherClaim(id) {
      var form = document.getElementById('claimForm' + id);
      form.submit();
    }
  </script>
@endsection
