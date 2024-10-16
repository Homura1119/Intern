<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\PlayerItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //プレイヤーテーブルからidとnameカラムを選択し、全てのプレイヤーデータを取得
        return response()->json(
            Player::query()->
            select(['id', 'name'])-> //idとnameカラムを取得
            get()); //すべてのデータを取得
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $player = new Player(); //Playerモデルのインスタンスを生成

        //PlayerモデルのPlayerShowメソッドを呼び出し、指定IDのプレイヤー情報を取得
        return response()->json($player->playerShow($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //更新処理
    public function update(Request $request, $id)
    {
        $player = new Player(); //Playerモデルのインスタンスを生成

        //プレイヤー情報をリクエストデータで更新
        $player->PlayerUpdate($id,$request->name,
        $request->hp, $request->mp, $request->money);

        //成功メッセージを返す
        return response()->json(['message'=>'sucsees']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //削除処理
    public function destroy($id)
    {
        $player = new Player(); //Playerモデルのインスタンスを生成

        //プレイヤーを削除
        $player->PlayerDestroy($id);

        //削除成功のメッセージを返す
        return response()->json(['message'=>'Player deleted successfully']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //作成処理
    public function create(Request $request)
    {
        $player = new Player(); //Playerモデルのインスタンスを生成

        //新しいプレイヤーを作成し、作成されたプレイヤーのIDを取得
        $newId = $player->playerCreate($request->name,
        $request->hp, $request->mp, $request->money);

        //作成されたプレイヤーIDをレスポンスとして返す
        return response()->json(['id'=>$newId]);
    }

    public function addItem(Request $request,$id)
    {
        //$playerItem = new PlayerItem();

        //プレイヤーIDとアイテムIDでプレイヤーの存在確認とアイテムの存在確認
        //player_itemsテーブルから、指定されたプレイヤーIDとアイテムIDに一致するレコードを取得
        $playerItem = DB::table('player_items')->where('player_id',$id)
            ->where('item_Id',$request->itemId)
                ->first();

        //もしプレイヤーアイテムテーブルにプレイヤーがアイテムを持っていたら
        if($playerItem)
        {
            //現在のアイテム数にリクエストで指定された数を加算
            $newCount = $playerItem->item_count + $request->count;

            //アイテム数を更新
            PlayerItem::UpdateItem($id,$request->itemId,$newCount);
        }
        else
        {
            //プレイヤーアイテムテーブルにプレイヤーがアイテムを持っていなかったら、新しいレコードを挿入
            PlayerItem::addItemInsert($id,$request->itemId,$request->count);

            //新しいアイテムのIDと数をJSON形式で返す
            return response()->json(['itemId'=>$request->itemId,'count'=>$request->count]);
         }

         //更新後のアイテム数をJSON形式で返す
        return response()->json(['item_count'=>$newCount]);
    }

    //アイテムを使用するメソッド
    public function useItem(Request $request,$id)
    {
        //プレイヤー情報をデータベースから取得
        $player=DB::table('players')
            ->where('id',$id)
                ->first();

        //プレイヤーのアイテム情報をデータベースから取得
        $playerItem=DB::table('player_items')
            ->where('player_id',$id)
                ->where('item_id',$request->itemId)
                    ->first();
        
        $item=DB::table('items')
            ->where('id',$request->itemId)
                ->select('value')
                    ->first();
                    
        $itemtype=DB::table('items')
            ->where('id',$request->itemId)
                ->select('type')
                    ->first();

        $nowItemCount=$playerItem->item_count;

        //アイテムが存在しないか、所持数が不足している場合
        if($player->hp>=200&&$player->mp>=200)
        {
            //アイテムを消費せず、現在の状態を返す
            return response()->json([
                'itemId'=>$request->itemId,
                'count'=>$nowItemCount,
                'player'=>[
                    'id'=>$id,
                    'hp'=>$player->hp,
                    'mp'=>$player->mp
                ]
            ]);
        }

        //新しいアイテムの所持数を計算
        $newItemCount=$nowItemCount-$request->count;

        $newHp=min($player->hp+$item->value*$request->count,200); 

        $newMp=min($player->mp+$item->value*$request->count,200);

        if($itemtype->type==1)
        {
            $newHp=min($player->hp+$item->value*$request->count,200); 
        }
        else if($itemtype->type==2)
        {
            $newMp=min($player->mp+$item->value*$request->count,200);
        }

        //プレイヤーのHPとMPを更新
        Player::PlayerHPMPUpdate($id,$newHp,$newMp);

        //アイテムの所持数を更新
        if($newItemCount>=0)
        {
            PlayerItem::PlayerUseItem($id,$request->itemId,$newItemCount);
        }
        else
        {
            //エラーレスポンスを返す
            return response()->json(['error'=>'Item not available or insufficient quantity',400]);
        }

        return response()->json([
            'ItemId'=>$request->itemId,
            'count'=>$newItemCount,
            'player'=>[
                'id'=>$id,
                'hp'=>$newHp,
                'mp'=>$newMp
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     //編集処理
    public function edit($id)
    {
        
    }
}
