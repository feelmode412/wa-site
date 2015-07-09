<?php namespace Webarq\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = ['code', 'type', 'value'];
    public $timestamps = false;

    public function scopeOfCodeType($query, $code, $type)
    {
        return $query->whereCode($code)->whereType($type)->first();
    }

}