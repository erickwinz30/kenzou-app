<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Member;
use App\Models\Layanan;
use App\Models\Challenge;
use Illuminate\Http\Request;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CategoryLayanan;

class ChallengeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('dashboard.challenge.index', [
      'challenges' => Challenge::all(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('dashboard.challenge.create', [
      'categories' => CategoryLayanan::all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $validatedData = $request->validate([
        'description' => 'required|max:255',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'unit' => 'required',
        'target' => 'required|numeric',
        'reward_type' => 'required',
        'reward_value' => 'nullable|numeric',
      ]);

      $newChallenge = Challenge::create($validatedData);
      Log::info('New Challenge created', ['challenge' => $newChallenge]);

      // $newChallengeId = Challenge::where('description', $newChallenge->description)->first();
      // Log::info('New Challenge ID', ['id' => $newChallengeId->id]);

      $allMember = Member::all();

      if ($allMember) {
        foreach ($allMember as $member) {
          $newChallengeProgress = ChallengeProgress::create([
            'challenge_id' => $newChallenge->id,
            'member_id' => $member->id,
            'progress' => 0,
          ]);

          Log::info('New Challenge Progress created', ['progress' => $newChallengeProgress]);
        }
      }


      return redirect('/dashboard/challenge')->with('success', 'Challenge baru berhasil ditambahkan!!!');
    } catch (\Exception $e) {
      $errorMessage = $e->getMessage();
      $errorTrace = $e->getTraceAsString();
      Log::error("Error in ChallengeController@store", ['error' => $errorMessage, 'trace' => $errorTrace]);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Challenge $challenge)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Challenge $challenge)
  {
    return view('dashboard.challenge.edit', [
      'challenge' => $challenge,
      'categories' => CategoryLayanan::all(),
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Challenge $challenge)
  {
    if ($request->description !== $challenge->description) {
      $rules['description'] = 'required|max:255';
    }

    if (date('Y-m-d\TH:i', strtotime($request->from_date)) !== date('Y-m-d\TH:i', strtotime($challenge->from_date))) {
      $rules['from_date'] = 'required|date';
    }

    if (date('Y-m-d\TH:i', strtotime($request->to_date)) !== date('Y-m-d\TH:i', strtotime($challenge->to_date))) {
      $rules['to_date'] = 'required|date';
    }

    if ($request->target != $challenge->target) {
      $rules['target'] = 'required|integer';
    }

    $validatedData = $request->validate($rules);

    if ($request->unit_id != $challenge->unit_id) {
      $currentUnit = Unit::where('id', $request->unit_id)->first();

      if (!$currentUnit) {
        $newUnit = Unit::create([
          'unit_type' => $request->unit_id,
        ]);

        Log::info('New Reward Unit created', ['unit' => $newUnit]);

        $newUnitId = Unit::where('unit', $request->unit_id)->first();

        $validatedData['unit_id'] = $newUnitId->id;
      } else {
        $validatedData['unit_id'] = $request->unit_id;
      }
    }

    $updatedChallenge = Challenge::where('id', $challenge->id)->update($validatedData);
    Log::info('Challenge updated', ['challenge' => $updatedChallenge]);

    return redirect('/dashboard/challenge')->with('success', 'Challenge berhasil diupdate!!!');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Challenge $challenge)
  {
    //
  }

  public function activeFetch(Request $request)
  {
    $dataActive = [];
    $activeChallenges = Challenge::where('is_active', true)->get();

    foreach ($activeChallenges as $challenge) {
      $dataActive[] = [
        'id' => $challenge->id,
        'description' => $challenge->description,
        'from_date' => Carbon::parse($challenge->from_date)->format('Y-m-d H:i:s'),
        'to_date' => Carbon::parse($challenge->to_date)->format('Y-m-d H:i:s'),
        'target' => $challenge->target,
        'unit' => $challenge->unit,
        'reward_type' => $challenge->reward_type,
        'reward_value' => $challenge->reward_value,
        'is_active' => $challenge->is_active,
      ];
    }

    if ($request->wantsJson()) {
      if ($activeChallenges->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataActive, 200);
      }
    }

    return $dataActive;
  }

  public function nonActiveFetch(Request $request)
  {
    $dataNonActive = [];
    $nonActiveChallenge = Challenge::where('is_active', false)->get();

    foreach ($nonActiveChallenge as $challenge) {
      $dataNonActive[] = [
        'id' => $challenge->id,
        'description' => $challenge->description,
        'from_date' => Carbon::parse($challenge->from_date)->format('Y-m-d H:i:s'),
        'to_date' => Carbon::parse($challenge->to_date)->format('Y-m-d H:i:s'),
        'target' => $challenge->target,
        'unit' => $challenge->unit,
        'reward_type' => $challenge->reward_type,
        'reward_value' => $challenge->reward_value,
        'is_active' => $challenge->is_active,
      ];
    }

    if ($request->wantsJson()) {
      if ($nonActiveChallenge->isEmpty()) {
        return response()->json([]);
      } else {
        return response()->json($dataNonActive, 200);
      }
    }

    return $dataNonActive;
  }

  public function toggleActivation(Request $request)
  {
    try {
      $challenge = Challenge::findOrFail($request->challengeId);
      $challenge->is_active = !$challenge->is_active;
      $challenge->save();

      return redirect('/dashboard/challenge')->with('success', 'Status challenge berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in ChallengeController@toggleActivation", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
