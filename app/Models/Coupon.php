<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    // Kiểm tra xem mã giảm giá có hiệu lực không
    public function isValid()
    {
        $today = now();
        return (!$this->start_date || $this->start_date <= $today) && (!$this->end_date || $this->end_date >= $today);
    }


    protected $dates = ['deleted_at'];
}
