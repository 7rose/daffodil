<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Staff;

class StateOk
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
        if(Session::has('id')){
            $id = Session::get('id');
            $me = Staff::find($id);

            if($id==1) {
                return $next($request);
            }else{
                if($me->locked || $me->hide){
                    $conf = [
                            'class'=>'warning', 
                            'title'=>'无法访问', 
                            'content'=>'您的账号处于锁定或隐藏状态,若需继续操作,请与管理员联系.', 
                        ];

                    return view('note')->with('conf',$conf);

                }else{
                    return $next($request);
                }

            }

        }else{
            $conf = [
                    'class'=>'danger', 
                    'title'=>'错误', 
                    'content'=>'未能成功设置登录SESSION,这个错误可能由于使用不合用的浏览器和APP造成的.', 
                ];

            return view('note')->with('conf',$conf);

        }
        
    }
}
