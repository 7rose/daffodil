<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;

use RestRose\Wechat\Publics\Menu;

class MenuController extends Controller
{
    /**
     * create menu
     *
     */
    public function createMenu ()
    {
        $array = ['button' => [ ['type' => 'view', 'name' => '时鲜特产', 'url'=>'https://lwjzc.com'],
                                ['type' => 'view', 'name' => '有奖推荐', 'url'=>'https://lwjzc.com/wechat/subscribe'],
                                ['type' => 'view', 'name' => '应用中心', 'url'=>'https://lwjzc.com/panel']
                 ]];
        $menu = new Menu;
        $menu->createMenu($array);
    }
}