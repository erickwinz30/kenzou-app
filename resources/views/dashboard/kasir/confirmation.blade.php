@extends('dashboard.layout.main')

@section('container')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mt-5">
        <div class="card-header text-center">
          <h2>Konfirmasi Transaksi</h2>
          <p>ID Transaksi: {{ $id }}</p>
        </div>
        <div class="card-body text-center">
          <div class="mb-4">
            <i class="bi bi-exclamation-circle-fill text-warning" style="font-size: 4rem;"></i>
          </div>
          {{-- <p class="mb-4">Terima kasih atas transaksi Anda. Pembayaran Anda sedang diproses.</p> --}}
          <div id="qrisAlert" class="alert alert-info" role="alert">
            <h4 class="alert-heading">Perhatian!</h4>
            <p>Silahkan konfirmasi pembayaran pada rekening yang digunakan untuk metode pembayaran QRIS.</p>
          </div>
          <div class="d-flex justify-content-evenly align-items-center">
            <form action="/dashboard/transaksiBaru/confirm-paid-off/{{ $id }}" method="POST"
              id="confirmUnpaidTransaction">
              @csrf
              <input type="hidden" name="id" value="{{ $id }}">
              <input type="hidden" name="is_paid_off" value="0">
              <button type="button" class="btn btn-warning" onclick="confirmUnpaidTransaction()">Belum Lunas</button>
            </form>
            <form action="/dashboard/transaksiBaru/confirm-paid-off/{{ $id }}" method="POST"
              id="confirmPaidTransaction">
              @csrf
              <input type="hidden" name="id" value="{{ $id }}">
              <input type="hidden" name="is_paid_off" value="1">
              <button type="button" class="btn btn-primary" onclick="confirmPaidTransaction()">Lunas</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmUnpaidTransaction() {
  Swal.fire({
  title: "Anda yakin transaksi belum lunas?",
  text: "Lakukan konfirmasi kembali!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#2980B9",
  cancelButtonColor: "#d33",
  confirmButtonText: "Ya, konfirmasi!"
  }).then((result) => {
  if (result.isConfirmed) {
  document.getElementById('confirmUnpaidTransaction').submit();
  }
  });
  }

  function confirmPaidTransaction() {
  Swal.fire({
  title: "Yakin transaksi telah lunas?",
  text: "Lakukan konfirmasi kembali jika diperlukan!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#2980B9",
  cancelButtonColor: "#d33",
  confirmButtonText: "Ya, konfirmasi!"
  }).then((result) => {
  if (result.isConfirmed) {
  document.getElementById("confirmPaidTransaction").submit();
  }
  });
  }

</script>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alert = document.getElementById('qrisAlert');
            if (alert) {
                alert.style.transition = 'opacity 1s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 1000);
            }
        }, 10000);
    });
</script>

@endpush