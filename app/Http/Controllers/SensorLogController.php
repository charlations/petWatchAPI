<?php

namespace App\Http\Controllers;

use App\SensorLog;
use App\Sensor;
use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensorLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
			$logs = SensorLog::join('pets', 'sensor_logs.idSensor', '=', 'pets.idSensor')
				->where('pets.idUser', Auth::guard('api')->id())
				->select('sensor_logs.*')->distinct()
				->get();
			return response()->json($logs, 200);
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
				'foodEmpty' => ['datatype' => 'boolean', 'nullable' => 'YES'],
				'waterEmpty' => ['datatype' => 'boolean', 'nullable' => 'YES'],
			];
			$details = ['method' => 'POST', 'url' => 'api/log'];
			$sensors = Sensor::join('pets', 'sensors.id', '=', 'pets.idSensor')
				->where('pets.idUser', Auth::guard('api')->id())
				->select('sensors.*')->distinct()
				->get();
			return response()->json(compact('details', 'requirements', 'sensors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			return response()->json(SensorLog::create($request->validate([
				'idSensor' => ['required', 'exists:sensors,id'],
				'foodEmpty' => ['boolean', 'nullable'],
				'waterEmpty' => ['boolean', 'nullable']
			])), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SensorLog $log
     * @return \Illuminate\Http\Response
     */
    public function show(SensorLog $log)
    {
			$pet = Pet::where('idSensor', $log->idSensor)->first();
			// return response()->json(compact('log', 'pet'), 200);
			if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Sensor Log not found', 404);
			}
			return response()->json($log, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SensorLog $log
     * @return \Illuminate\Http\Response
     */
    public function edit(SensorLog $log)
    {
			$pet = Pet::where('idSensor', $log->idSensor)->first();
			if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Sensor Log not found', 404);
			}
			$requirements = [
				'_method' => ['datatype' => 'string', 'nullable' => 'OBLIGATORY', 'value' => 'PUT'],
				'idSensor' => ['datatype' => 'int', 'nullable' => 'NO'],
				'foodEmpty' => ['datatype' => 'boolean', 'nullable' => 'YES'],
				'waterEmpty' => ['datatype' => 'boolean', 'nullable' => 'YES'],
			];
			$details = ['method' => 'POST', 'url' => 'api/log/'.$log->id, 'notes' => 'method may also be PUT/PATCH with x-www-form-urlencoded body'];
			$sensors = Sensor::join('pets', 'sensors.id', '=', 'pets.idSensor')
				->where('pets.idUser', Auth::guard('api')->id())
				->select('sensors.*')->distinct()
				->get();
			return response()->json(compact('details', 'requirements', 'sensors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SensorLog  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SensorLog $log)
    {
			$pet = Pet::where('idSensor', $log->idSensor)->first();
			if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Sensor Log not found', 404);
			}
			$log->update($request->validate([
				'idSensor' => ['required', 'exists:sensors,id'],
				'foodEmpty' => ['boolean', 'nullable'],
				'waterEmpty' => ['boolean', 'nullable']
			]));
			return response()->json($log, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SensorLog  $sensorLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(SensorLog $log)
    {
			$pet = Pet::where('idSensor', $log->idSensor)->first();
			if (!$pet || $pet->idUser != Auth::guard('api')->id()) {
				return response()->json('Sensor Log not found', 404);
			}
			$log->delete();
			return response()->json('Sensor Log deleted', 202);
    }
}
