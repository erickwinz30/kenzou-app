<?php

namespace App\Http\Controllers;

use App\Models\RewardUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RewardUnitController extends Controller
{
  public function index(Request $request)
  {
    try {
      // Fetch units with optional search functionality
      $units = RewardUnit::where('unit', 'like', '%' . $request->get('q') . '%')
        ->select('id', 'unit')
        ->get();

      return response()->json($units);
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian unit error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return response()->json(['error' => $errorMessage], 500);
    }
  }

  public function fetchUnits()
  {
    // Fetch all reward units
    $units = RewardUnit::all(['id', 'unit']); // Assuming 'name' is the field you want to display
    return response()->json($units);
  }

  public function search(Request $request)
  {
    try {
      $unit = $request->q; // Mengambil istilah pencarian dari parameter 'q'
      Log::info("Pencarian Unit", ['searchResult' => $unit]);

      $searchResult = RewardUnit::where('unit', 'like', '%' . $unit . '%')->get();

      $unitData = [];

      foreach ($searchResult as $unit) {
        $unitData[] = [
          'id' => $unit->id,
          'unit' => $unit->unit,
        ];
      }

      return response()->json($unitData);
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error('Pencarian unit error: ', ['message' => $errorMessage, 'trace' => $errorTrace]);
      return response()->json(['error' => $errorMessage], 500);
    }
  }
}
