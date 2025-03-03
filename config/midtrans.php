<?php

return [
  'server_key' => env('SB_MIDTRANS_SERVER_KEY'),
  'client_key' => env('SB_MIDTRANS_CLIENT_KEY'),
  'is_production' => env('SB_MIDTRANS_IS_PRODUCTION', false),
  'is_sanitized' => env('SB_MIDTRANS_IS_SANITIZED', true),
  'is_3ds' => env('SB_MIDTRANS_IS_3DS', true),
];
