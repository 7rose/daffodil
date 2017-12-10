<?php

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            [
                'level'     => 1,
                'org'       => 1,
                'hide'      => true,
                'show'      => false,
                'name'      => '系统管理者',
                'name_en'   => 'Root User',
                'created_by' => 1,
            ],[
                'level'     => 2,
                'org'       => 1,
                'hide'      => false,
                'show'      => true,
                'name'      => '董事长',
                'name_en'   => 'Chairman',
                'created_by' => 1,
            ],[
                'level'     => 3,
                'org'       => 1,
                'hide'      => false,
                'show'      => true,
                'name'      => '总经理',
                'name_en'   => 'President',
                'created_by' => 1,
            ],[
                'level'     => 4,
                'org'       => 1,
                'hide'      => false,
                'show'      => true,
                'name'      => '副总经理',
                'name_en'   => 'Vice President',
                'created_by' => 1,
            ],[
                'level'     => 5,
                'org'       => 1,
                'hide'      => false,
                'show'      => true,
                'name'      => '总监',
                'name_en'   => 'Officer',
                'created_by' => 1,
            ],[
                'level'     => 6,
                'org'       => 1,
                'hide'      => false,
                'show'      => true,
                'name'      => '经理',
                'name_en'   => 'Manager',
                'created_by' => 1,
            ],[
                'level'     => 7,
                'org'       => 1,
                'hide'      => false,
                'show'      => false,
                'name'      => '员工',
                'name_en'   => 'satff',
                'created_by' => 1,
            ],[
                'level'     => 8,
                'org'       => 10,
                'name'      => '主管',
                'hide'      => false,
                'show'      => true,
                'name_en'   => 'Shop Owner',
                'created_by' => 1,
            ],[
                'level'     => 9,
                'org'       => 10,
                'hide'      => false,
                'show'      => true,
                'name'      => '店长',
                'name_en'   => 'Shop Header',
                'created_by' => 1,
            ],[
                'level'     => 10,
                'org'       => 10,
                'hide'      => false,
                'show'      => false,
                'name'      => '店员',
                'name_en'   => 'Shop Assistant',
                'created_by' => 1,
            ],[
                'level'     => 8,
                'org'       => 12,
                'hide'      => false,
                'show'      => false,
                'name'      => '高级',
                'name_en'   => 'Shop Assistant',
                'created_by' => 1,
            ],[
                'level'     => 9,
                'org'       => 12,
                'hide'      => false,
                'show'      => false,
                'name'      => '中级',
                'name_en'   => 'Shop Assistant',
                'created_by' => 1,
            ],[
                'level'     => 10,
                'org'       => 12,
                'hide'      => false,
                'show'      => false,
                'name'      => '正常',
                'name_en'   => 'Shop Assistant',
                'created_by' => 1,
            ],[
                'level'     => 8,
                'org'       => 11,
                'hide'      => false,
                'show'      => false,
                'name'      => '钻石',
                'name_en'   => 'diamend',
                'created_by' => 1,
            ],[
                'level'     => 9,
                'org'       => 11,
                'hide'      => false,
                'show'      => false,
                'name'      => '黄金',
                'name_en'   => 'gold',
                'created_by' => 1,
            ],[
                'level'     => 10,
                'org'       => 11,
                'hide'      => false,
                'show'      => false,
                'name'      => '白银',
                'name_en'   => 'silver',
                'created_by' => 1,
            ],
        ]);
    }
}

