<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
        return response()->json(["id"=>$newId]);
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
