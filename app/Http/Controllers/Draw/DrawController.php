<?php

namespace App\Http\Controllers\Draw;

use App\Model\PrizeModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class DrawController extends Controller
{
    public function luckadd(){
        return view("luck.luckadd");
    }

    public function luckmedo(){
        $uid=$_POST['uid'];
        $data=PrizeModel::where(["uid"=>$uid])->get()->toArray();
        foreach ($data as $k=>$v) {
            if($v['level'] > 0){
                $response=[
                    "code"=>0,
                    "msg"=>"您已中奖,把更多机会留给其他人吧",
                ];
                echo json_encode($response,true);die;
            }
        }
        if(count($data) >= 3){
            $response=[
                "code"=>0,
                "msg"=>"抱歉~您的机会已经用尽",
            ];
            echo json_encode($response,true);die;
        }
        $level=$this->getPrizeLevel();

        $dataInfo=[
            "uid"=>$uid,
            "level"=>$level['level'],
        ];
        $res=PrizeModel::insert($dataInfo);
        if($res){
            $response=[
                "code"=>0,
                "msg"=>"ok",
                "data"=>[
                    "level"=>$level['level'],
                    "msg"=>$level['msg'],
                ]
            ];
            return json_encode($response,true);
        }
    }

    public function getPrizeLevel(){
        $level=mt_rand(1,10000);//随机数生成中奖区间
        if($level>0 && $level <17){//判断用户该操作是否中奖

            $prizeNumber=$this->status($level);//引入函数，获取奖品区间&&奖编号
            $count=PrizeModel::where(['level'=>$prizeNumber['jiang']])->count();//查库

            if($count>=$prizeNumber['status']){//奖品是否上限
                $status=0;
                $msg="很遗憾，未能中奖";
            }else{
                $status=$prizeNumber['status'];
                $msg=$prizeNumber['msg'];
            }
        }else{//本身未中奖
            $status=0;
            $msg="很遗憾，未能中奖";
        }
        $response=[
            "level"=>$status,
            "msg"=>$msg
        ];
        return $response;
    }

    public function status($level){//根据随机出的数字判断中奖值
        if($level==1){
            $status=1;$jiang=1;$msg="恭喜，一等奖";
        }elseif($level==2 || $level==3){
            $status=2;$jiang=2;$msg="恭喜，二等奖";
        }elseif($level==4 || $level==5 || $level==6){
            $status=3;$jiang=3;$msg="恭喜，三等奖";
        }else{
            $status=4;$jiang=10;$msg="恭喜，风和日丽奖";
        }
        $arr=[
            "status"=>$status,
            "jiang"=>$jiang,
            "msg"=>$msg,
        ];
        return $arr;
    }
}
