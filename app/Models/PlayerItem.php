<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlayerItem extends Model
{
    //タイムスタンプを無効にするプロパティ
    public $timestamps = false;
    use HasFactory;

    //アイテムを更新するメソッド
    //指定されたプレイヤーIDとアイテムIDに基づいて、アイテムの数を更新する
    public function UpdateItem($playerId,$itemId,$count)
    {
        //クエリビルダを使用して、指定された条件に一致するレコードを
        PlayerItem::query()->where('player_id',$playerId)
            ->where('item_id',$itemId)
                ->update(['item_count'=>$count]);
    }

    //アイテムを追加するメソッド
    //指定されたプレイヤーIDとアイテムIDに基づいて、新しいアイテムをデータベースに挿入する
    public static function addItemInsert($playerId,$itemId,$count)
    {
        //クエリビルダを使用して、新しいレコードを挿入
        PlayerItem::query()->insert(['player_id'=>$playerId,'item_id'=>$itemId,'item_count'=>$count]);
    }

    public static function usePlayerItem($playerId,$itemId,$count)
    {
        PlayerItem::query()->where('player_id',$playerId)
            ->where('item_id',$itemId)
                ->update(['item_count'=>$count]);
    }
}
