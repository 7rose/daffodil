<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Cookie;
use Input;

use App\Wechat;

use RestRose\Wechat\Publics\Helpers;
use RestRose\Wechat\Publics\Oauth2;

/**
 * need openid
 *
 */
class WechatOpenid
{
    public $cookie_minutes=20160;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('openid')){
            return $next($request);
        } elseif (!Session::has('openid') && Cookie::has('openid')) {
            Session::put('openid', Cookie::get('openid'));
            return $next($request);
        }elseif(!Session::has('openid') && !Cookie::has('openid') && Session::has('id')) {
            $me = Wechat::where('staff_id', Session::get('id'))->first();
            if(count($me)) {
                Session::put('openid', $me->openid);
                return $next($request);
            }else{
                Session::put('openid', 'staff>'.Session::get('id'));
                Wechat::create(['staff_id' => Session::get('id'), 'openid' => 'staff>'.Session::get('id')]);
                return $next($request);
            }
        }else{
            // use wechat
            $helper = new Helpers;
            if($helper->useWechat()) {
                $oauth2 = new Oauth2;
                if(Input::has('code')){
                    $oauth2->getAuthToken(); # session set
                    $oauth2->getUserInfo(); # update user info

                    Cookie::queue('openid', Session::get('openid'), $this->cookie_minutes); # cookie
                    return $next($request);
                }else {
                    $oauth2->getCode();
                }
            }else{
                abort(405);
            }
        }
        // abort(403);
    }

    //end
}









