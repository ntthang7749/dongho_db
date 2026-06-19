<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    protected $fillable = ['user_id', 'type', 'input', 'output', 'tokens_used'];
}
