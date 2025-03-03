@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>QRIS Pembayaran</h1>
</div>
<!-- End Page Title -->

<section class="section dashboard">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Scan QRIS untuk Membayar</h5>
          <div id="qris-container"></div>
          <p id="payment-status"></p>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}">
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                document.getElementById('payment-status').textContent = 'Pembayaran berhasil!';
            },
            onPending: function(result) {
                document.getElementById('payment-status').textContent = 'Menunggu pembayaran...';
            },
            onError: function(result) {
                document.getElementById('payment-status').textContent = 'Pembayaran gagal!';
            },
        });
    });
</script>

@endsection
