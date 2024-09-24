<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class Chat extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'chats';
}
