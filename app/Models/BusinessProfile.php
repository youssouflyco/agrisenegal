<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
   protected $fillable = [
      'user_id',
      'cni',
      'cni_front_photo',
      'cni_back_photo',
      'farm_name',
      'production_type',
      'farm_photo',
      'business_name',
      'business_type',
      'business_photo',
      'status',
      'rejection_reason',
      'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }
}
