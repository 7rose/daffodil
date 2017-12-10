<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Cookie;
use Input;
use App\Http\Controllers\StaffController;
use RestRose\Wechat\Enterprise\Api;
use App\Staff;

class StaffOnly
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
        $staff = new StaffController;

        if(Session::has('id')){
            return $next($request);
        }else{
            if(Cookie::has('id')){
                $me = Cookie::get('id');
                if(!Session::has('id')) Session::put('id', $me);
                return $next($request);

            }else{
                if(str_contains($user_agent, 'MicroMessenger')) {

                    // rate
                    if(!Cookie::has('open_id')){
                        //wechat
                        if(Input::get('code')){
                            //带code
                            $b = new Api;
                            $info = $b->getAuthInfo();

                            if(array_has($info, 'UserId') && $info['UserId'] !='') {
                                // Enterprise Wechat
                                $me = Staff::where('sn', $info['UserId'])->value('id');
                                if(count($me) != 1) die('Middleware:staff_only-wrong staff sn');

                                if(!Session::has('id')) Session::put('id', $me);
                                Cookie::queue('id', $me, 10080);
                                return $next($request);

                            }elseif(array_has($info, 'OpenId') && $info['OpenId'] !=''){
                                // 2 days
                                Cookie::queue('open_id', $info['OpenId'], 2880);
                                return  $staff->login();
                            }else{ 
                                return  $staff->login();
                            }
                            
                        }else{
                            //不带code
                            $a = new Api;
                            $a->getAuthCode();

                        }

                    }else{
                        return  $staff->login();
                    }

                }else{
                    return  $staff->login();

                }

            }

        }
        
    }
}
