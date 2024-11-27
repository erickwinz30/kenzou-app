@extends('dashboard.layout.main')

@section('container')
  <style>
    #pelanggan-container {
      position: absolute;
      max-height: 150px;
      overflow-y: auto;
      background-color: white;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      left: 0;
      /* right: 20px; */
      width: 100%;
      margin-top: 10px;
      box-sizing: border-box;
      z-index: 999;
    }
  </style>

  <div class="pagetitle">
    <h1>Transaksi Baru</h1>
  </div>
  <!-- End Page Title -->

  @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif

  @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  @endif

  <section class="section dashboard">
    <div class="row">
      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row left-container">
          <!-- Layanan Select -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="layanan-header">
                  <h5 class="card-title">Pilih Layanan</h5>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gx-3">
                    @foreach ($layanans as $layanan)
                      <div class="col">
                        <div class="card shadow h-85" style="border-radius: 15px">
                          <div class="card-body p-3 d-flex justify-content-between align-items-center">
                            <div>
                              <h5 class="card-title p-0">{{ $layanan->nama_layanan }}</h5>
                              <p class="card-text">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                            </div>
                            <div>
                              <button href="#" class="btn btn-primary add-item" data-id="{{ $layanan->id }}"
                                data-name="{{ $layanan->nama_layanan }}" data-price="{{ $layanan->harga }}">
                                <i class="bi bi-plus-circle"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    <!-- Add more cards here -->
                  </div>
                </div>
                <!-- Additional content here -->
              </div>
            </div>
          </div>
          <!-- End Layanan Select -->

          <!-- Pelanggan Select -->
          <div class="col-12">
            <div class="card">
              <div class="card-body" style="box-sizing: border-box" id="container-informasi-pelanggan">
                <div class="layanan-header">
                  <h5 class="card-title">Informasi Pelanggan</h5>
                </div>
                <div class="item">
                  <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">+62</span>
                    <input type="text" inputmode="numeric" class="form-control" id="nomor_telepon"
                      placeholder="No. telepon" aria-label="Username" aria-describedby="addon-wrapping" maxlength="15" />
                    <button class="btn btn-primary" id="tambahNoPelanggan">Tambah</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Pelanggan Select -->
        </div>
      </div>
      <!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">
        <!-- Detail Transaksi -->
        <div class="card">
          <div class="card-body">
            <form action="/dashboard/transaksiBaru" method="POST">
              @csrf
              <h5 class="card-title">Detail Transaksi</h5>
              {{-- Container detail layanan pada transaksi --}}
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="card-text fw-semibold my-auto">Tgl Transaksi</h6>
                <p class="card-text">{{ $tanggal_transaksi }}</p>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2" id="containerNoPelanggan">
                <h6 class="card-text fw-semibold my-auto">Informasi Pelanggan</h6>
              </div>
              <h6 class="card-text fw-semibold m-0" id="card_item">Item</h6>
              <div class="item_transaksi" id="item_transaksi">
                <!-- Items will be added here dynamically -->
              </div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="card-text fw-semibold m-0">Total: </h6>
                <p class="card-text" id="total-display">Rp. 0</p>
              </div>
              <div class="mb-3" id="rank-description-container"></div>
              <div class="mb-3" id="voucher-description-container"></div>
              <div class="mb-3" id="challenge-description-container"></div>
              <div class="mb-3">
                <h6 class="card-text fw-semibold mb-3" id="card_item">Keterangan</h6>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Silahkan diisi disini.."></textarea>
              </div>
              {{-- Container Subtotal & Metode Pembayaran --}}
              <div class="card shadow">
                <div class="card-body" style="background-color: #eeeeee; border-radius: 5px">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-text fw-semibold mt-4">Subtotal:</h6>
                    <input type="hidden" name="total_harga" value="0" id="inputHarga" />
                    <p class="card-text" id="subtotal_value">Rp. 0</p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-text fw-semibold mt-3">Metode Pembayaran:</h6>
                    <div class="d-flex justify-content-end align-items-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios2"
                          value="qris" checked />
                        <label class="form-check-label" for="exampleRadios2"> QRIS </label>
                      </div>
                      <div class="form-check ms-3">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios1"
                          value="tunai" />
                        <label class="form-check-label" for="exampleRadios1"> Tunai </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
        <!-- End Detail Transaksi -->
      </div>
      <!-- End Right side columns -->
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Select all the "Tambah" buttons
      const addItemButtons = document.querySelectorAll(".add-item");
      const addNoPelanggan = document.getElementById("tambahNoPelanggan");
      const containerItem = document.getElementById("item_transaksi");
      const subtotalElement = document.getElementById("subtotal_value");
      const inputNomorTelepon = document.getElementById("nomor_telepon");
      const containerNoPelanggan = document.getElementById("container-informasi-pelanggan");
      let inputHarga = document.getElementById("inputHarga");
      let total = 0;
      let itemTotal = 0;
      let badgeDiscountPercentage = 0;
      let voucherDiscountPercentage = 0;
      let challengeDiscountPercentage = 0;
      let rankDiscountPercentage = 0;
      let subtotal = 0;
      let timeoutId;

      // fuction untuk update subtotal
      function updateSubtotal(badgeDiscount, amount, voucherDiscount, rankDiscount) {
        total += amount;
        console.log("Total Layanan: " + total);

        let badgeDiscountResult = total * badgeDiscount;
        console.log("Badge Discount: " + badgeDiscountResult);
        let voucherDiscountResult = total * voucherDiscount;
        console.log("Voucher Discount: " + voucherDiscountResult);
        let rankDiscountResult = total * rankDiscount;
        console.log("Rank Discount: " + rankDiscountResult);

        let totalDiscount = badgeDiscountResult + voucherDiscountResult + rankDiscountResult;
        console.log("Total Discount: " + totalDiscount);

        subtotal = total - totalDiscount;
        console.log("Subtotal: " + subtotal);

        inputHarga.value = subtotal;
        subtotalElement.textContent = `Rp. ${subtotal.toLocaleString()}`;
      }

      function checkMemberVoucher(phoneNumber) {
        fetch("/dashboard/transaksiBaru/fetch-check-voucher", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({
              nomor_telepon: phoneNumber,
            }),
          })
          .then((response) => response.json())
          .then((data) => {
            if (data && data.length > 0) {
              createVoucherElement();
              createVoucherItemElement(data);
            }
          })
          .catch((error) => console.error("Error Fetching Data:", error));
      }

      function checkMemberChallenge(phoneNumber) {
        setTimeout(() => {
          fetch("/dashboard/transaksiBaru/fetch-check-challenge", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
              },
              body: JSON.stringify({
                nomor_telepon: phoneNumber,
              }),
            })
            .then((response) => response.json())
            .then((data) => {
              if (data && data.length > 0) {
                data.forEach((progressChallenge) => {
                  console.log("Progress Challenge: " + progressChallenge.description);
                });
                createChallengeElement();
                createChallengeItemElement(data);
              }
            })
            .catch((error) => console.error("Error Fetching Data:", error));
        }, 800);
      }

      function checkMemberRank(phoneNumber) {
        fetch("/dashboard/transaksiBaru/fetch-check-rank", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({
              nomor_telepon: phoneNumber,
            }),
          })
          .then((response) => response.json())
          .then((data) => {
            if (data && Object.keys(data).length > 0) {
              rankDiscountPercentage = data.discount;
              createRankElement(data.rank, data.discount);
              console.log("Rank: " + data.rank + " with discount: " + data.discount);
            } else {
              rankDiscountPercentage = 0;
              console.log("No rank data found.");
            }
          })
          .catch((error) => console.error("Error Fetching Data:", error));
      }

      function customerInformationElement() {
        let noHp = document.getElementById("nomor_telepon").value;
        let infoPelanggan = document.createElement("div");
        infoPelanggan.classList.add("d-flex", "justify-content-end", "align-items-center");

        infoPelanggan.innerHTML = `
          <input type="hidden" name="nomor_telepon" id="nomor_telepon_value" value="${noHp}">
          <p class="card-text my-auto">${noHp}</p>
          <button type="button" class="btn p-0 ms-2 my-auto" id="remove-pelanggan">
            <i class="bi bi-x-circle"></i>
          </button>
        `;

        // // checkMemberRank(noHp);
        // checkMemberVoucher(noHp);
        // checkMemberChallenge(noHp);

        addNoPelanggan.disabled = true;

        updateSubtotal(0, 0, 0, 0);

        document.getElementById("containerNoPelanggan").appendChild(infoPelanggan);
        document.getElementById("nomor_telepon").value = "";

        // document.getElementById("remove-pelanggan").addEventListener("click", function() {
        //   // event.preventDefault();
        //   let noHp = document.getElementById("nomor_telepon");
        //   noHp.value = "";
        //   infoPelanggan.remove();
        //   addNoPelanggan.disabled = false;

        //   deleteMemberElement();
        // });

        removePelanggan(infoPelanggan, addNoPelanggan, null);
      }

      function memberInformationClickElement(memberName, phoneNumber, badgeName, badgeDiscount, rank, rankDiscount,
        pelangganContainer) {
        let infoPelanggan = document.createElement("div");
        infoPelanggan.classList.add("d-flex", "justify-content-end", "align-items-center");
        badgeDiscountPercentage = badgeDiscount;
        rankDiscountPercentage = rankDiscount;

        if (rank !== null) {
          createRankElement(rank, rankDiscountPercentage);
        }

        infoPelanggan.innerHTML = `
            <div class="d-flex justify-content-between align-items-center my-2" data-member-name="${memberName}" data-nomor-telepon="${phoneNumber}" 
            data-badge-name="${badgeName}" data-badge-discount="${badgeDiscount}">
              <input type="hidden" name="nomor_telepon" id="nomor_telepon_value" value="${phoneNumber}">
              <p class="card-text my-auto">${memberName} / ${badgeName}</p>
              <button type="button" class="btn p-0 ms-2 my-auto" id="remove-pelanggan">
                <i class="bi bi-x-circle"></i>
              </button>
            </div>
          `;

        // checkMemberRank(phoneNumber);
        checkMemberVoucher(phoneNumber);
        checkMemberChallenge(phoneNumber);

        addNoPelanggan.disabled = true;

        updateSubtotal(badgeDiscountPercentage, 0, voucherDiscountPercentage, rankDiscountPercentage);

        document.getElementById("containerNoPelanggan").appendChild(infoPelanggan);
        document.getElementById("nomor_telepon").value = "";

        pelangganContainer.remove();

        removePelanggan(infoPelanggan, addNoPelanggan, pelangganContainer);

        console.log(phoneNumber);
      }

      function memberInformationListElement(containerDataPelanggan, pelangganContainer) {
        const memberName = containerDataPelanggan.getAttribute("data-member-name");
        const nomorTelepon = containerDataPelanggan.getAttribute("data-nomor-telepon");
        const badgeName = containerDataPelanggan.getAttribute("data-badge-name");
        const badgeDiscount = containerDataPelanggan.getAttribute("data-badge-discount");
        const rank = containerDataPelanggan.getAttribute("data-rank");
        const rankDiscount = containerDataPelanggan.getAttribute("data-rank-discount");
        badgeDiscountPercentage = badgeDiscount;
        rankDiscountPercentage = rankDiscount;


        let infoPelanggan = document.createElement("div");
        infoPelanggan.classList.add("d-flex", "justify-content-end", "align-items-center");

        infoPelanggan.innerHTML = `
            <input type="hidden" name="nomor_telepon" id="nomor_telepon_value" value="${nomorTelepon}">
            <p class="card-text my-auto">${memberName} / ${badgeName}</p>
            <button type="button" class="btn p-0 ms-2 my-auto" id="remove-pelanggan">
              <i class="bi bi-x-circle"></i>
            </button>
          `;

        // checkMemberRank(nomorTelepon);
        checkMemberVoucher(nomorTelepon);
        checkMemberChallenge(nomorTelepon);

        addNoPelanggan.disabled = true;

        updateSubtotal(badgeDiscountPercentage, 0, 0, rankDiscountPercentage);

        document.getElementById("containerNoPelanggan").appendChild(infoPelanggan);

        if (rank !== null) {
          createRankElement(rank, rankDiscountPercentage);
        }
        document.getElementById("nomor_telepon").value = "";

        pelangganContainer.remove();

        console.log(nomorTelepon);

        removePelanggan(infoPelanggan, addNoPelanggan, pelangganContainer);
      }

      function removePelanggan(infoPelanggan, addNoPelanggan, pelangganContainer) {
        document.getElementById("remove-pelanggan").addEventListener("click", function() {
          // event.preventDefault();
          let noHp = document.getElementById("nomor_telepon");
          noHp.value = "";
          badgeDiscountPercentage = 0;
          voucherDiscountPercentage = 0;
          rankDiscountPercentage = 0;

          updateSubtotal(badgeDiscountPercentage, 0, voucherDiscountPercentage, rankDiscountPercentage);

          infoPelanggan.remove();
          addNoPelanggan.disabled = false;

          if (pelangganContainer) {
            pelangganContainer.remove();
          }

          deleteMemberElement();
        });
      }

      function createVoucherElement() {
        const voucherElement = document.createElement("div");
        voucherElement.classList.add("col-12");
        voucherElement.setAttribute("id", "voucher-element");

        voucherElement.innerHTML = `
          <div class="card">
            <div class="card-body">
              <div class="voucher-header">
                <h5 class="card-title">List Voucher Member</h5>
              </div>
              <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2" id="voucher-list-container">
              </div>
            </div>
          </div>
        `;

        document.querySelector(".left-container").appendChild(voucherElement);
      }

      function createVoucherItemElement(data) {
        data.map((voucher) => {
          let voucherItem = document.createElement("div");
          voucherItem.classList.add("col");

          voucherItem.innerHTML = `
            <div class="card shadow h-85" style="border-radius: 15px">
              <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title p-0">${voucher.name}</h5>
                  <p class="card-text">${voucher.description}</p>
                  <p class="card-text">Memperoleh Diskon: ${voucher.discount * 100}%</p>
                </div>
                <div>
                  <button href="#" class="btn btn-primary voucher-add-item" data-id="${voucher.id}"
                  data-name="${voucher.name}" data-discount="${voucher.discount}"><i class="bi bi-plus-circle"></i>
                  </button>
                </div>
              </div>
            </div>
          `;

          document.querySelector("#voucher-list-container").appendChild(voucherItem);

          clickVoucherItem();
        });
      }

      function clickVoucherItem() {
        const voucherAddItemButtons = document.querySelectorAll(".voucher-add-item");

        voucherAddItemButtons.forEach((button) => {
          // Hapus event listener sebelumnya jika ada
          button.removeEventListener("click", handleVoucherClick);

          // Tambahkan event listener baru
          button.addEventListener("click", handleVoucherClick);
        });
      }

      function handleVoucherClick(event) {
        event.preventDefault();

        const voucherId = this.getAttribute("data-id");
        const voucherName = this.getAttribute("data-name");
        const discount = parseFloat(this.getAttribute("data-discount"));

        voucherDiscountPercentage = discount;

        updateSubtotal(badgeDiscountPercentage, 0, voucherDiscountPercentage, rankDiscountPercentage);

        const voucherElement = document.getElementById("voucher-description-container");
        voucherElement.innerHTML = `
          <h6 class="card-text fw-semibold">Voucher</h6>
          <div class="d-flex justify-content-between align-items-center">
            <p class="card-text my-auto">${voucherName}</p>
            <button type="button" class="btn p-0 ms-2 my-auto" id="remove-voucher">
              <i class="bi bi-x-circle"></i>
            </button>
          </div>
        `;

        // Disable all other voucher buttons
        const voucherAddItemButtons = document.querySelectorAll(".voucher-add-item");
        voucherAddItemButtons.forEach((button) => {
          button.disabled = true;
        });

        // Enable the remove button for the selected voucher
        document.getElementById("remove-voucher").addEventListener("click", function() {
          voucherElement.innerHTML = "";
          voucherDiscountPercentage = 0;
          updateSubtotal(badgeDiscountPercentage, 0, voucherDiscountPercentage, rankDiscountPercentage);

          // Enable all voucher buttons again
          voucherAddItemButtons.forEach((button) => {
            button.disabled = false;
          });
        });
      }

      function createChallengeElement() {
        const challengeElement = document.createElement("div");
        challengeElement.classList.add("col-12");
        challengeElement.setAttribute("id", "challenge-element");

        challengeElement.innerHTML = `
          <div class="card">
            <div class="card-body">
              <div class="challenge-header">
                <h5 class="card-title">List Challenge Member</h5>
              </div>
              <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2" id="challenge-list-container">
              </div>
            </div>
          </div>
        `;

        document.querySelector(".left-container").appendChild(challengeElement);
      }

      function createChallengeItemElement(data) {
        data.forEach((challenge) => {
          let challengeItem = document.createElement("div");
          challengeItem.classList.add("col");

          let selectedLayananItem = document.querySelectorAll(".layanan-added-item");
          let isButtonEnabled = false;

          if (selectedLayananItem.length > 0) {
            selectedLayananItem.forEach((selectedItem) => {
              let selectedLayananId = parseInt(selectedItem.getAttribute('data-layanan-id'));
              if (selectedLayananId === challenge.layanan_id) {
                isButtonEnabled = true;
              }
            });
          }

          challengeItem.innerHTML = `
            <div class="card shadow h-85" style="border-radius: 15px">
              <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title p-0">${challenge.description}</h5>
                  <p class="card-text">Selesai pada tanggal: ${challenge.to_date}</p>
                  <p class="card-text">Gratis ${challenge.layanan_name}</p>
                </div>
                <div>
                  <button href="#" class="btn btn-primary challenge-add-item" 
                  ${isButtonEnabled ? '' : 'disabled'}
                  data-id="${challenge.id}"
                  data-description="${challenge.description}" 
                  data-layanan-id="${challenge.layanan_id}" 
                  data-layanan-name="${challenge.layanan_name}" 
                  data-layanan-price="${challenge.layanan_price}">
                    <i class="bi bi-plus-circle"></i>
                  </button>
                </div>
              </div>
            </div>
          `;

          document.querySelector("#challenge-list-container").appendChild(challengeItem);
        });

        clickChallengeItem();
      }

      function clickChallengeItem() {
        const challengeAddItemButtons = document.querySelectorAll(".challenge-add-item");

        challengeAddItemButtons.forEach((button) => {
          // Hapus event listener sebelumnya jika ada
          button.removeEventListener("click", handleChallengeClick);

          // Tambahkan event listener baru
          button.addEventListener("click", handleChallengeClick);
        });
      }

      function handleChallengeClick(event) {
        event.preventDefault();

        const challengeId = this.getAttribute("data-id");
        const challengeDescription = this.getAttribute("data-description");
        const challengeLayananId = this.getAttribute("data-layanan-id");
        const challengeLayananName = this.getAttribute("data-layanan-name");
        const challengeLayananPrice = parseInt(this.getAttribute("data-layanan-price"));

        const layananItemsPrice = document.querySelectorAll(".layanan-price");

        layananItemsPrice.forEach((price) => {
          let layananId = price.getAttribute("data-layanan-id");
          let layananPrice = parseInt(price.getAttribute("data-price"));

          if (layananId === challengeLayananId) {
            price.textContent = `Rp. 0`;

            updateSubtotal(badgeDiscountPercentage, -layananPrice, voucherDiscountPercentage,
              rankDiscountPercentage);
          }
        });

        const challengeElement = document.getElementById("challenge-description-container");
        challengeElement.innerHTML = `
            <h6 class="card-text fw-semibold">Challenge</h6>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="card-text my-auto">${challengeDescription}</p>
                <p class="card-text my-auto">Gratis ${challengeLayananName}</p>
              </div>
              <button type="button" class="btn p-0 ms-2 my-auto" id="remove-challenge" data-layanan-id=${challengeLayananId}>
              <i class="bi bi-x-circle"></i>
              </button>
            </div>
        `;

        // Disable all other challenge buttons
        const challengeAddItemButtons = document.querySelectorAll(".challenge-add-item");
        challengeAddItemButtons.forEach((button) => {
          button.disabled = true;
        });

        // Disable all other voucher buttons
        const voucherAddItemButtons = document.querySelectorAll(".voucher-add-item");
        voucherAddItemButtons.forEach((button) => {
          button.disabled = true;
        });

        // remove voucher element
        const voucherElement = document.getElementById("voucher-description-container");
        if (voucherElement) {
          voucherElement.innerHTML = "";
        }

        // Enable the remove button for the selected challenge
        document.getElementById("remove-challenge").addEventListener("click", function() {
          challengeElement.innerHTML = "";

          layananItemsPrice.forEach((price) => {
            let layananId = price.getAttribute("data-layanan-id");

            if (layananId === challengeLayananId) {
              price.textContent = `Rp. ${challengeLayananPrice.toLocaleString()}`;

              updateSubtotal(badgeDiscountPercentage, challengeLayananPrice, voucherDiscountPercentage,
                rankDiscountPercentage);
            }
          });


          // Enable all challenge buttons again
          challengeAddItemButtons.forEach((button) => {
            button.disabled = false;
          });

          // Enable all voucher buttons again
          voucherAddItemButtons.forEach((button) => {
            button.disabled = false;
          });
        });
      }

      function createRankElement(memberRank, memberRankDiscount) {
        let rank = memberRank;
        let discount = memberRankDiscount;

        const rankElement = document.getElementById("rank-description-container");

        rankElement.innerHTML = `
          <h6 class="card-text fw-semibold">Leaderboard Rank</h6>
          <div class="d-flex justify-content-between align-items-center">
            <p class="card-text my-auto">${rank}</p>
            <p class="card-text my-auto">Diskon: ${discount * 100}%</p>
          </div>
        `;
      }

      function deleteMemberElement() {
        const voucherElement = document.getElementById("voucher-element");
        if (voucherElement) {
          voucherElement.remove();
        }

        const challengeElement = document.getElementById("challenge-element");
        if (challengeElement) {
          challengeElement.remove();
        }

        const rankDescriptionContainer = document.getElementById("rank-description-container");
        if (rankDescriptionContainer) {
          rankDescriptionContainer.innerHTML = "";
        }

        const voucherDescriptionContainer = document.getElementById("voucher-description-container");
        if (voucherDescriptionContainer) {
          voucherDescriptionContainer.innerHTML = "";
        }

        const challengeDescriptionContainer = document.getElementById("challenge-description-container");
        if (challengeDescriptionContainer) {
          challengeDescriptionContainer.innerHTML = "";
        }
      }

      // Add click event listeners to each button
      addItemButtons.forEach((button) => {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          // Retrieve data attributes from the clicked button
          const itemId = this.getAttribute("data-id");
          const itemName = this.getAttribute("data-name");
          const itemPrice = parseInt(this.getAttribute("data-price"));

          // Create a new div to hold the item details
          const itemDiv = document.createElement("div");
          itemDiv.classList.add("item", "d-flex", "justify-content-between", "align-items-center",
            "layanan-added-item");
          itemDiv.setAttribute("data-layanan-id", itemId);
          itemDiv.setAttribute("data-layanan-name", itemName);
          itemDiv.setAttribute("data-layanan-price", itemPrice);

          // Add the item name and price to the new div
          itemDiv.innerHTML = `
            <input type="hidden" class="layanan-item-id" name="layanan_id[]" value="${itemId}">
            <p class="card-text my-auto">${itemName}</p>
            <div class="d-flex justify-content-end align-items-center">
            <p class="card-text my-auto layanan-price" data-layanan-id=${itemId} data-price=${itemPrice}>Rp. ${itemPrice}</p>
            <button type="button" class="btn p-0 ms-2 remove-item">
              <i class="bi bi-x-circle"></i>
            </button>
            </div>
          `;

          button.disabled = true;

          // Append the new item div to the transaction container
          // containerTransaksi = document.getElementById('item_transaksi');
          containerItem.appendChild(itemDiv);

          // Update the total price
          itemTotal += itemPrice;
          const totalDisplay = document.getElementById("total-display");
          totalDisplay.textContent = `Rp. ${itemTotal.toLocaleString()}`;

          const challengeDescriptionContainer = document.getElementById("challenge-description-container");

          if (challengeDescriptionContainer) {
            let challengeAddButton = document.querySelectorAll(".challenge-add-item");

            challengeAddButton.forEach((button) => {
              let buttonId = button.getAttribute("data-layanan-id");

              if (buttonId === itemId) {
                button.disabled = false;
              }
            });
          }

          updateSubtotal(badgeDiscountPercentage, itemPrice, voucherDiscountPercentage,
            rankDiscountPercentage);

          // Add event listener to the remove button
          itemDiv.querySelector(".remove-item").addEventListener("click", function() {
            // let removeItemPrice = parseInt(this.getAttribute("data-price"));
            let removeItemPrice = parseInt(itemDiv.querySelector(".layanan-price").getAttribute(
              "data-price"));
            const challengeDescriptionContainer = document.getElementById(
              "challenge-description-container");
            const voucherAddButton = document.querySelectorAll(".voucher-add-item");

            if (challengeDescriptionContainer.innerHTML !== "") {
              console.log('Challenge Description Container is not null');
              const challengeRemoveButton = document.getElementById("remove-challenge");
              const challengeAddButton = document.querySelectorAll(".challenge-add-item");
              const challengeLayananId = challengeRemoveButton.getAttribute("data-layanan-id");

              if (challengeLayananId === itemId) {
                challengeDescriptionContainer.innerHTML = "";
                challengeAddButton.forEach((button) => {
                  button.disabled = true;
                });
              }

              updateSubtotal(badgeDiscountPercentage, itemPrice, voucherDiscountPercentage,
                rankDiscountPercentage);
            }

            // Update the total price
            itemTotal -= removeItemPrice;
            console.log(itemTotal);
            const totalDisplay = document.getElementById("total-display");
            totalDisplay.textContent = `Rp. ${itemTotal.toLocaleString()}`;

            voucherAddButton.forEach((button) => {
              button.disabled = false;
            });

            itemDiv.remove();
            updateSubtotal(badgeDiscountPercentage, -itemPrice, voucherDiscountPercentage,
              rankDiscountPercentage);
            button.disabled = false;
          });
        });
      });

      //tombol tambah nomor telepon
      addNoPelanggan.addEventListener("click", function(event) {
        const noHp = document.getElementById("nomor_telepon").value;

        fetch("/dashboard/transaksiBaru/nomor_telepon", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({
              nomor_telepon: noHp,
            }),
          })
          .then((response) => response.json())
          .then((data) => {
            let pelangganContainer = document.getElementById("pelanggan-container");

            if (data && data.length > 0) {
              // Periksa apakah data tidak kosong
              // Jika elemen pelangganContainer tidak ada, buat elemen baru
              if (!pelangganContainer) {
                pelangganContainer = document.createElement("div");
                pelangganContainer.classList.add("pelanggan-container");
                pelangganContainer.setAttribute("id", "pelanggan-container");
                containerNoPelanggan.appendChild(pelangganContainer);
              } else {
                // Kosongkan isi pelangganContainer jika sudah ada
                pelangganContainer.innerHTML = "";
              }

              // Tambahkan data ke pelangganContainer
              data.forEach((item) => {
                // const containerDataPelanggan = document.createElement("div");
                console.log(item.nama);

                memberInformationClickElement(item.nama, item.nomor_telepon, item
                  .badgeName, item.badgeDiscount, item.rank, item.rankDiscount, pelangganContainer);
              });
            } else {
              customerInformationElement();
              // Jika data kosong, hapus pelangganContainer jika sudah ada
              if (pelangganContainer) {
                pelangganContainer.remove(); // Hapus elemen pelangganContainer dari DOM
              }
            }
          })
          .catch((error) => console.error("Error Fetching Data:", error));
      });

      //proses input nomor telepon
      inputNomorTelepon.addEventListener("input", function(event) {
        event.preventDefault();
        clearTimeout(timeoutId); // Hentikan timeout sebelumnya, jika ada

        timeoutId = setTimeout(() => {
          let noHp = document.getElementById("nomor_telepon").value;

          fetch("/dashboard/transaksiBaru/nomor_telepon", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
              },
              body: JSON.stringify({
                nomor_telepon: noHp,
              }),
            })
            .then((response) => response.json())
            .then((data) => {
              let pelangganContainer = document.getElementById("pelanggan-container");

              if (inputNomorTelepon.value !== "") {
                if (data && data.length > 0) {
                  // Periksa apakah data tidak kosong
                  // Jika elemen pelangganContainer tidak ada, buat elemen baru
                  if (!pelangganContainer) {
                    pelangganContainer = document.createElement("div");
                    pelangganContainer.classList.add("pelanggan-container");
                    pelangganContainer.setAttribute("id", "pelanggan-container");
                    containerNoPelanggan.appendChild(pelangganContainer);
                  } else {
                    // Kosongkan isi pelangganContainer jika sudah ada
                    pelangganContainer.innerHTML = "";
                  }

                  // Tambahkan data ke pelangganContainer
                  data.forEach((item) => {
                    const containerDataPelanggan = document.createElement("div");
                    console.log(item.nama);

                    containerDataPelanggan.innerHTML = `
                          <div class="d-flex justify-content-between align-items-center my-2" data-member-name="${item.nama}" data-nomor-telepon="${item.nomor_telepon}" 
                          data-badge-name="${item.badgeName}" data-badge-discount="${item.badgeDiscount}" data-rank="${item.rank}" data-rank-discount="${item.rankDiscount}">
                            <p class="card-text my-0"><strong>${item.nama}</strong> / ${item.email}</p>
                            <p class="card-text fw-semibold my-0">${item.nomor_telepon}</p>
                          </div>
                          <hr>
                        `;

                    pelangganContainer.appendChild(containerDataPelanggan);
                  });
                } else {
                  // Jika data kosong, hapus pelangganContainer jika sudah ada
                  if (pelangganContainer) {
                    pelangganContainer.remove(); // Hapus elemen pelangganContainer dari DOM
                  }
                }
              } else {
                // Jika input kosong, hapus pelangganContainer jika sudah ada
                if (pelangganContainer) {
                  pelangganContainer.remove(); // Hapus elemen pelangganContainer dari DOM
                }
              }
            })
            .catch((error) => console.error("Error Fetching Data:", error));
        }, 800); // Delay selama 800ms
      });

      //event klik saat list member muncul
      document.getElementById("container-informasi-pelanggan").addEventListener("click", function(event) {
        event.preventDefault();
        const target = event.target;
        const containerDataPelanggan = target.closest("[data-nomor-telepon]");
        let pelangganContainer = document.getElementById("pelanggan-container");

        if (containerDataPelanggan) {
          memberInformationListElement(containerDataPelanggan, pelangganContainer);
        }
      });
    });
  </script>
@endsection
