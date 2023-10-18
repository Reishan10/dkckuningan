<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Soal extends Model
{
    use HasFactory;


    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'soal';

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

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    protected $fillable = [
        'soal',
        'bobot_nilai',
        'golongan_id',
    ];
}
