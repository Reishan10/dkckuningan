<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Notifikasi extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'notifikasi';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

     public function receiver()
     {
         return $this->belongsTo(User::class, 'receiver_id', 'id');
     }
 
     public function sender()
     {
         return $this->belongsTo(User::class, 'sender_id', 'id');
     }

    protected $fillable = [
        'receiver_id',
        'sender_id',
        'message',
    ];
}
