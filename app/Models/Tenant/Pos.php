<?php

namespace App\Models\Tenant;


use Illuminate\Database\Eloquent\SoftDeletes;

class Pos extends ModelTenant
{
    use SoftDeletes;


    protected $with = ['establishment','sales', 'user'];
    protected $fillable = [
        'id',
        'user_id',
        'establishment_id',
        'open_amount',
        'close_amount',
        'sales_count',
        'status',
        'created_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'deleted_at'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function sales()
    {
        return $this->hasMany(PosSales::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function active()
    {
        $pos = auth()->user()->pos()->orderBy('id', 'desc')->where('status', 'open');
        $id = $pos->count() ? $pos->first()->id : null;
        return $id;
    }

}
