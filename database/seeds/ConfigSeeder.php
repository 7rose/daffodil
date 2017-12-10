<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
            [
                'parent_id' => '',
                'list'    => 'gender',
                'text'    => '男',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'gender',
                'text'    => '女',
                'text_en' => 'Female',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'gender',
                'text'    => '其他',
                'text_en' => 'Other',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '手机',
                'text_en' => 'Moblie Phone',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '邮件',
                'text_en' => 'Email',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '地址',
                'text_en' => 'Address',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '邮编',
                'text_en' => 'Post Code',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => 'QQ',
                'text_en' => 'QQ',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '微信',
                'text_en' => 'Wechat',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => 'FaceBook',
                'text_en' => 'FaceBook',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => 'Twitter',
                'text_en' => 'Twitter',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => 'WhatsApp',
                'text_en' => 'WhatsApp',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '设备',
                'text_en' => 'Device',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => 'UUID',
                'text_en' => 'UUID',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'relations',
                'text'    => '微信OpenID',
                'text_en' => 'Openid of Wechat',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type', // Product Type
                'text'    => '珠宝饰品',
                'text_en' => 'jewelry',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '箱包',
                'text_en' => 'bags',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '服装',
                'text_en' => 'clothing',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '配件',
                'text_en' => 'accessory',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '附件/包装',
                'text_en' => 'etc',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '礼品/赠品',
                'text_en' => 'gift',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'product_type',
                'text'    => '其他',
                'text_en' => 'others',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color', // STONE color
                'text'    => '无色/透明',
                'text_en' => 'colourless',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '粉',
                'text_en' => 'pink',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '红',
                'text_en' => 'red',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '青',
                'text_en' => 'syan',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '绿',
                'text_en' => 'green',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '蓝',
                'text_en' => 'blue',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '黄/金色',
                'text_en' => 'yellow',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '橙',
                'text_en' => 'orange',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '紫',
                'text_en' => 'purple',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '茶色',
                'text_en' => 'tan',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '黑',
                'text_en' => 'black',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '白/银色',
                'text_en' => 'white',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'stone_color',
                'text'    => '多彩',
                'text_en' => 'multi-colored',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type', // sub of id = 16
                'text'    => '发饰',
                'text_en' => 'Hair Accessory',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '项链',
                'text_en' => 'necklace',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '耳饰',
                'text_en' => 'earrings',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '胸针',
                'text_en' => 'brooch',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '袖扣',
                'text_en' => 'cufflink',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '手饰/手链',
                'text_en' => 'bacelet',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '戒指',
                'text_en' => 'ring',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '脚饰',
                'text_en' => 'anklet',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '工艺品/摆件',
                'text_en' => 'art works',
                'extra'   => '',
            ],[
                'parent_id' => 16,
                'list'    => 'product_type',
                'text'    => '其他',
                'text_en' => 'others',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type', // sub of 17
                'text'    => '手袋',
                'text_en' => 'tote',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '背包',
                'text_en' => 'backpack',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '肩包',
                'text_en' => 'shoulder bag',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '公文包',
                'text_en' => 'briefcase',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '旅行包',
                'text_en' => 'traveling bag',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '手拿包',
                'text_en' => 'hand bag',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '化妆包',
                'text_en' => 'cosmetic bag',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '钱包',
                'text_en' => 'wallet',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '钥匙包',
                'text_en' => 'key bag',
                'extra'   => '',
            ],[
                'parent_id' => 17,
                'list'    => 'product_type',
                'text'    => '首饰盒',
                'text_en' => 'casket',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type', // sub of 19
                'text'    => '钥匙扣',
                'text_en' => 'key ring',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '太阳镜',
                'text_en' => 'sun glasses',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '伞',
                'text_en' => 'umbrella',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '围巾/丝巾',
                'text_en' => 'scarf',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '腰带/皮带',
                'text_en' => 'belt',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '手套',
                'text_en' => 'gloves',
                'extra'   => '',
            ],[
                'parent_id' => 19,
                'list'    => 'product_type',
                'text'    => '钟表',
                'text_en' => 'watch',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_type',
                'text'    => '生鲜',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_type',
                'text'    => '特产',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_type',
                'text'    => '点心',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_unit',
                'text'    => '件',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_unit',
                'text'    => '份',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_unit',
                'text'    => '公斤',
                'text_en' => 'Male',
                'extra'   => '',
            ],[
                'parent_id' => '',
                'list'    => 'lwj_unit',
                'text'    => '市斤',
                'text_en' => 'Male',
                'extra'   => '',
            ],
        ]);
    }
}
