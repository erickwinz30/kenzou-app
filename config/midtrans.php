<?php

// return [
//   'server_key' => env('MIDTRANS_SERVER_KEY'),
//   'client_key' => env('MIDTRANS_CLIENT_KEY'),
//   'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
//   'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
//   'is_3ds' => env('MIDTRANS_IS_3DS', true),
// ];

return [
  'server_key' => env('MIDTRANS_SERVER_KEY'),
  'client_key' => env('MIDTRANS_CLIENT_KEY'),
  'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
  'is_sanitized' => (bool) env('MIDTRANS_IS_SANITIZED', true),
  'is_3ds' => (bool) env('MIDTRANS_IS_3DS', true),
  'verify_ssl' => (bool) env('MIDTRANS_VERIFY_SSL', true),
  'ca_bundle_path' => env('MIDTRANS_CA_BUNDLE_PATH'),
  'snap_enabled_payments' => env('MIDTRANS_SNAP_ENABLED_PAYMENTS', ''),
  'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
];
