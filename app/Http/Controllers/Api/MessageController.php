<?php

namespace App\Http\Controllers\Api;

use App\Models\Enterprise;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\TextCard;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function sendMsg()
    {
        $app = $this->makeWork();
        $messenger = $app->messenger;

        $msg = request()->input('msg', '');

        $data = request()->all();
        $validator = Validator::make($data, [
            'msg' => 'required',
            'to' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->failed('请输入收件人和信息');
        }

        $messenger->toUser($data['to'])->send($data['msg']);

        return $this->success(
            [
                'msg' => '发送完成',
            ]
        );

    }

    public function sendCard()
    {
        $app = $this->makeWork();
        $messenger = $app->messenger;

        $data = request()->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'desc' => 'required',
            'url' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->failed('请输入完整资料');
        }

        $message = new TextCard([
            'title' => $data['title'],
            'description' => $data['desc'],
            'url' => $data['url'],
        ]);
        $messenger->message($message)->toUser($data['to'])->send();
        return $this->success(
            [
                'msg' => '发送完成',
            ]
        );
    }

    private function makeWork()
    {
        $ent = request()->input('ent', '');
        if (!$ent) {
            return $this->failed('企业编号不能为空');
        }

        $enterprise = Enterprise::where('code', $ent)->first();

        $config = [
            'corp_id' => $enterprise->corp_id,
            'agent_id' => $enterprise->agent_id,
            'secret' => $enterprise->secret,
        ];
        return Factory::Work($config);
    }
}
