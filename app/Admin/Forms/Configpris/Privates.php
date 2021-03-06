<?php

namespace App\Admin\Forms\Configpris;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Privates extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '私密视频|私聊';

    public $option_name = 'configpris_privates';
    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        //dump($request->all());
        $this->BaseValidator($request->all())->validate();

        $res = \App\Models\Option::updateOrCreate(
            ['option_name' => $this->option_name], ['option_value' => json_encode($request->all(), JSON_UNESCAPED_UNICODE)]
        );

        if ($res) {
            admin_success('操作成功.');
        } else {
            admin_error('操作失败.');
        }

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->switch('video_private_on', '私密视频开关')->states([
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => '关闭', 'color' => 'default'],
        ]);

        $this->radio('video_private_switch',"观看方式")->options([1=>'vip观看',2=>'钻石付费'])->when(1, function (Form $form) {

        })->when(2, function (Form $form) {

            $form->number('video_private_switch_pay', '费用')->min(0);

        });

        $this->switch('chat_private_on',"私聊开关")->states([
            'on'  => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => '关闭', 'color' => 'default'],
        ]);

        $this->radio('chat_private_switch',"私聊限制方式")->options([1=>'身份认证',2=>'vip',3=>'身份认证+vip',4=>'无限制']);

    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $res = \App\Models\Option::where(['option_name'=>$this->option_name])->get()->toArray();

        return empty($res)?[]:json_decode($res[0]['option_value'],true);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function BaseValidator(array $data)
    {
        return Validator::make($data, [

        ]);
    }
}
