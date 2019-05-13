<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
		
		/* Define table name */
		protected $table = 'sensor_logs';
		/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['idSensor', 'foodEmpty', 'waterEmpty'];
}
