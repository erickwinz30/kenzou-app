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
use App\Models\ChallengePrize;

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
        'target' => 'required|numeric',
        'unit' => 'required',
        'layanan_id' => 'required',
        'reward_value' => 'nullable|numeric',
      ]);

      $newChallenge = Challenge::create($validatedData);
      Log::info('New Challenge created', ['challenge' => $newChallenge]);
      Log::info('New Challenge ID', ['challengeId' => $newChallenge->id]);

      $allMember = Member::all();

      if ($allMember) {
        foreach ($allMember as $member) {
          $newChallengeProgress = ChallengeProgress::create([
            'challenge_id' => $newChallenge->id,
            'member_id' => $member->id,
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
    $rules = [];

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

    if ($request->unit !== $challenge->unit) {
      $rules['unit'] = 'required';
    }

    if ($request->layanan_id != $challenge->layanan_id) {
      $rules['layanan_id'] = 'required|integer';
    }

    $validatedData = $request->validate($rules);

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
        'from_date' => Carbon::parse($challenge->from_date)->format('d-m-Y H:i:s'),
        'to_date' => Carbon::parse($challenge->to_date)->format('d-m-Y H:i:s'),
        'target' => $challenge->target,
        'unit' => $challenge->unit,
        'layanan' => $challenge->layanan->nama_layanan,
        'is_repeatable' => $challenge->is_repeatable,
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
        'from_date' => Carbon::parse($challenge->from_date)->format('d-m-Y H:i:s'),
        'to_date' => Carbon::parse($challenge->to_date)->format('d-m-Y H:i:s'),
        'target' => $challenge->target,
        'unit' => $challenge->unit,
        'layanan' => $challenge->layanan->nama_layanan,
        'is_repeatable' => $challenge->is_repeatable,
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

  public function toggleRepeatable(Request $request)
  {
    try {
      $challenge = Challenge::where('id', $request->challengeId)->first();
      $challenge->is_repeatable = !$challenge->is_repeatable;
      $challenge->save();

      return redirect('/dashboard/challenge')->with('success', 'Status perulangan challenge berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in ChallengeController@toggleRepeatable", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }

  public function toggleActivation(Request $request)
  {
    try {
      $challenge = Challenge::where('id', $request->challengeId)->first();
      $challenge->is_active = !$challenge->is_active;
      $challenge->save();

      return redirect('/dashboard/challenge')->with('success', 'Status aktivasi challenge berhasil diubah!!!');
    } catch (\Exception $e) {
      Log::error("Error in ChallengeController@toggleActivation", ['error' => $e->getMessage()]);
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }
}
