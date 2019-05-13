<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    /* Soft delete users */
		use SoftDeletes;
		protected $dates = ['deleted_at'];
		/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'description'];
}
