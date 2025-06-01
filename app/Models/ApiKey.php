<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'key',
    ];

    /**
     * Boot method pour générer automatiquement l'UUID et la clé aléatoire
     * avant la création d'une nouvelle entrée.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Génère un UUID si pas déjà fourni
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            // Génère une clé aléatoire de 60 caractères si pas déjà fournie
            if (empty($model->key)) {
                do {
                    $random = Str::random(60);
                } while (self::where('key', $random)->exists());
                $model->key = $random;
            }
        });
    }

    /**
     * Relation vers l'utilisateur propriétaire de cette clé.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
