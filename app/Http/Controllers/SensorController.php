<?php

namespace App\Http\Controllers;

use App\Sensor;
use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
				// dd(Auth::guard('api')->id());
				$sensors = Sensor::join('pets', 'sensors.id', '=', 'pets.idSensor')
					->where('pets.idUser', Auth::guard('api')->id())
					->select('sensors.*')->distinct()
					->get();
				return response()->json($sensors, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
				$requirements = [
					'type' => ['datatype' => 'varchar(191)', 'nullable' => 'NO'],
					'description' => ['datatype' => 'text', 'nullable' => 'YES'],
				];
				$details = ['method' => 'POST', 'url' => 'api/sensor'];
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
        return response()->json(Sensor::create($request->validate([
					'type' => ['required', 'min:2'],
					'description' => 'nullable'
				])), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function show(Sensor $sensor)
    {
				$pet = Pet::where('idSensor', $sensor->id)->first();
				if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
					return response()->json('Sensor not found', 404);
				}
				return response()->json($sensor, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function edit(Sensor $sensor)
    {
				$pet = Pet::where('idSensor', $sensor->id)->first();
				if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
					return response()->json('Sensor not found', 404);
				}
				$requirements = [
					'_method' => ['datatype' => 'string', 'nullable' => 'OBLIGATORY', 'value' => 'PUT'],
					'type' => ['datatype' => 'varchar(191)', 'nullable' => 'NO'],
					'description' => ['datatype' => 'text', 'nullable' => 'YES'],
				];
				$details = ['method' => 'POST', 'url' => 'api/sensor/'.$sensor->id, 'notes' => 'method may also be PUT/PATCH with x-www-form-urlencoded body'];
				return response()->json(compact('details', 'requirements'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sensor $sensor)
    {
				// return response()->json($request->all());
				$pet = Pet::where('idSensor', $sensor->id)->first();
				if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
					return response()->json('Sensor not found', 404);
				}
				$sensor->update($request->validate([
					'type' => ['required', 'min:2'],
					'description' => 'nullable'
				]));
				return response()->json($sensor, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sensor $sensor)
    {
				$pet = Pet::where('idSensor', $sensor->id)->first();
				if ($pet && $pet->idUser != Auth::guard('api')->id()) {
					return response()->json('Sensor not found', 404);
				}
				$sensor->delete();
				return response()->json('Sensor deleted', 202);
    }
}
