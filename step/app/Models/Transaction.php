<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'amount',
    ];
    public function sender(){
        return $this->belongsTo(Account::class,'sender_id');
    }

    public function recipient(){
        return $this->belongsTo(Account::class,'recipient_id');
    }
}
