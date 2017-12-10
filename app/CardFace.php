<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardFace extends Model
{
    protected $table = 'card_face';
    protected $fillable = ['item_id', 'ratio', 'title', 'content', 'limit', 'transferable', 'locked', 'created_by'];
}

