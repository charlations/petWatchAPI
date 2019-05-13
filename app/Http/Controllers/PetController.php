<?php

namespace App\Http\Controllers;

use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				$pets = Pet::where('idUser', Auth::guard('api')->id())->get();
				return response()->json($pets, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
			$requirements = [
				'idSensor' => ['datatype' => 'int', 'nullable' => 'NO'],
				'name' => ['datatype' => 'varchar(191)', 'nullable' => 'NO'],
			];
			$details = ['method' => 'POST', 'url' => 'api/pet'];
			return response()->json(compact('details', 'requirements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			$request->validate([
				'idSensor' => ['required', 'exists:sensors,id'],
				'name' => ['required', 'min:2']
			]);
			return response()->json(Pet::create([
				'idUser' => Auth::guard('api')->id(),
				'idSensor' => $request->idSensor,
				'name' => $request->name
			]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show(Pet $pet)
    {
			dd($pet);
			if ($pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Pet not found', 404);
			}
			return response()->json($pet, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function edit(Pet $pet)
    {
			if ($pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Pet not found', 404);
			}
			$requirements = [
				'_method' => ['datatype' => 'string', 'nullable' => 'OBLIGATORY'], 
				'idSensor' => ['datatype' => 'int', 'nullable' => 'NO'],
				'name' => ['datatype' => 'varchar(191)', 'nullable' => 'NO'],
			];
			$details = ['method' => 'POST', 'url' => 'api/pet'.$pet->id, 'notes' => 'method may also be PUT/PATCH with x-www-form-urlencoded body'];
			return response()->json(compact('details', 'requirements'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
			if ($pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Pet not found', 404);
			}
			$request->validate([
				'idSensor' => ['required', 'exists:sensors,id'],
				'name' => ['required', 'min:2']
			]);
			$pet->update([
				'idUser' => Auth::guard('api')->id(),
				'idSensor' => $request->idSensor,
				'name' => $request->name
			]);
			return response()->json($pet, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
			if ($pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Pet not found', 404);
			}
			$pet->delete();
			return response()->json('Pet deleted', 202);
    }
}
