<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Pendaftaran extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'pendaftaran';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function penilaian()
    {
      return $this->hasMany(Penilaian::class); 
    }

    protected $fillable = [
        'user_id',
        'nta',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pangkalan',
        'golongan_id',
        'berkas',
        'status'
    ];
}
