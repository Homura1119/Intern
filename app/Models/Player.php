<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    //絶対に消したらダメ！！
    //タイムスタンプを無効にしている(レコードの作成・更新時に自動でcreated_at, updated_atを追加しない)
    public $timestamps = false;
    use HasFactory;

    //プレイヤーの情報を取得するメソッド(idとnameのみ取得)
    public function PlayerShow($id)
    {
        //プレイヤーIDでデータを検索し、idとnameのみを選択して最初の結果を返す
        return(Player::query()->where('id', $id)->select(['id', 'name'])->first());
    }

    //プレイヤー情報を更新するメソッド
    public function PlayerUpdate($id,$name,$hp,$mp,$money)
    {
        //プレイヤーIDでデータを検索し、name,hp,mp,moneyのカラムを指定された値に更新
        Player::query()->
        where('id',$id)->
        update( [
            'name' => $name,
            'hp' => $hp,
            'mp' => $mp,
            'money' => $money,
        ]);
    }

    public function PlayerHPMPUpdate($id,$hp,$mp)
    {
        Player::query()
            ->where('id',$id
                )->update([
                    'hp'=>$hp,
                    'mp'=>$mp
            ]);
    }

    //プレイヤー情報を削除するメソッド
    public function PlayerDestroy($id)
    {
        //プレイヤーIDでデータを検索し、該当レコードを削除
        return(Player::query()->where("id",$id)->delete());
    }

    //新しいプレイヤーを作成するメソッド
    public function PlayerCreate($name,$hp,$mp,$money)
    {
        //name,hp,mp,moneyの値で新しいレコードを作成し、そのIDを返す
        return(Player::query()->insertGetId(
            [
                'name' => $name,
                'hp' => $hp,
                'mp' => $mp,
                'money' => $money,
            ]
        ));
    }
}