<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SharedData extends Model
{

    protected $fillable = ['username', 'description'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toFormattedDateString();
    }

}
