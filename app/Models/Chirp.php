<?php

namespace App\Models;

/**
 * 
 */
use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chirp extends Model
{
    use HasFactory;

    /**
     * Chrips 
     */
    protected $fillable = [
        'message',
    ];

    /**
     * 
     */
    protected $idspatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    /**
     * add new "belongs to"relationship to Chirp Model
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
