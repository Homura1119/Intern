<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;
    use HasFactory;

    // public function ItemShow($id)
    // {
    //     //プレイヤーIDでデータを検索し、idとnameのみを選択して最初の結果を返す
    //     return(Player::query()->where('id', $id)->select(['value'])->first());
    // }
}
