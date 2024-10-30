<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
  public function index(Request $request)
  {
    try {
      // Fetch units with optional search functionality
      $units = Unit::where('unit_type', 'like', '%' . $request->get('q') . '%')
        ->select('id', 'unit_type')
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
    $units = Unit::all(['id', 'unit_type']); // Assuming 'name' is the field you want to display
    return response()->json($units);
  }

  public function search(Request $request)
  {
    try {
      $unit = $request->q; // Mengambil istilah pencarian dari parameter 'q'
      Log::info("Pencarian Unit", ['searchResult' => $unit]);

      $searchResult = Unit::where('unit_type', 'like', '%' . $unit . '%')->get();

      $unitData = [];

      foreach ($searchResult as $unit) {
        $unitData[] = [
          'id' => $unit->id,
          'unit_type' => $unit->unit_type,
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
