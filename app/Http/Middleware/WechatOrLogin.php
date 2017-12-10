<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Cookie;
use App\Member;
use Input;
use Request;

use RestRose\Wechat\Enterprise\Api;

class WechatOrLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if(Session::has('id')){
            return $next($request);

        }else if(Cookie::get('id') && Session::has('id') === false){

            $id = Cookie::get('id');
            Cookie::has('deviceid') ? $deviceid = Cookie::get('deviceid') : $deviceid = '';
            $rec = Member::find($id);

            if(count($rec)){

                if(!Session::has('id')) Session::put('id', $rec->id);
                if(!Session::has('name')) Session::put('name', $rec->name);
                if(!Session::has('deviceid')) Session::put('deviceid', $deviceid);

                return $next($request);

            }else{
                return "未授权用户";

            }

        }else{
            if (strpos($user_agent, 'MicroMessenger') === false) {
                // 非微信浏览器
                $redirect_path = Request::path();
                return view('login')->with('redirect_path', $redirect_path);

            } else {
                //微信内
                if(Input::get('code')){
                    //带code
                    $fnc = new Api;
                    $arr = $fnc->getAuthInfo();

                    $rec = Member::where('work_id', $arr['UserId'])->first();
                    Session::put('id', $rec->id);
                    Session::put('name', $rec->name);

                    Cookie::queue('id', $rec->id, 20160);

                    return $next($request);
                    
                }else{
                    //不带code
                    $fnc = new Api;
                    $fnc->getAuthCode();
                }
            }
        }
    }
}