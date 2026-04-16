<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('midtrans.server_key');
    Config::$clientKey = config('midtrans.client_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized', true);
    Config::$is3ds = config('midtrans.is_3ds', true);

    // Midtrans ApiRequestor expects CURLOPT_HTTPHEADER key to always exist.
    Config::$curlOptions = [
      CURLOPT_HTTPHEADER => [],
    ];

    $verifySsl = (bool) config('midtrans.verify_ssl', true);
    $caBundlePath = config('midtrans.ca_bundle_path');

    if (!$verifySsl) {
      Config::$curlOptions = [
        CURLOPT_HTTPHEADER => [],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
      ];
    } elseif (!empty($caBundlePath)) {
      Config::$curlOptions = [
        CURLOPT_HTTPHEADER => [],
        CURLOPT_CAINFO => $caBundlePath,
      ];
    }
  }

  public function createTransaction(array $params)
  {
    $transaction = Snap::createTransaction($params);
    return [
      'token' => $transaction->token,
      'redirect_url' => $transaction->redirect_url,
    ];
  }

  public function getTransactionStatus(string $orderId): array
  {
    $status = MidtransTransaction::status($orderId);

    return json_decode(json_encode($status), true) ?? [];
  }
}
