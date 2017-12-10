<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'parent_id'          => 0,
                'level'              => 1,
                'org'                => true,
                'for_org'            => '',
                'independent'        => false,
                'allow_root'         => false,
                'allow_admin'        => false,
                'allow_master'       => false,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => true,
                'order'              => 1,
                'name'               => '根部门',
                'name_en'            => 'Root Department',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 1,
                'level'              => 2,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '乐万家',
                'name_en'            => 'RestRose',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 2,
                'level'              => 3,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '董事会',
                'name_en'            => 'The Board',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 3,
                'level'              => 4,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '总经理',
                'name_en'            => 'President',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 4,
                'level'              => 5,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '副总经理',
                'name_en'            => 'Vice President',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 5,
                'level'              => 6,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '市场部',
                'name_en'            => 'Market DP.',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 5,
                'level'              => 6,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 2,
                'name'               => '资源部',
                'name_en'            => 'Resources DP.',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 5,
                'level'              => 6,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '技术部',
                'name_en'            => 'Technical DP.',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 5,
                'level'              => 6,
                'org'                => false,
                'for_org'            => 1,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 4,
                'name'               => '运营部',
                'name_en'            => 'Operational DP.',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 6,
                'level'              => 7,
                'org'                => true,
                'for_org'            => '',
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => true,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 1,
                'name'               => '门店',
                'name_en'            => 'Shops',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 6,
                'level'              => 7,
                'org'                => true,
                'for_org'            => '',
                'independent'        => true,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => true,
                'hide'               => false,
                'order'              => 2,
                'name'               => '客户',
                'name_en'            => 'Customer',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 7,
                'level'              => 7,
                'org'                => true,
                'for_org'            => '',
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => true,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '供应商',
                'name_en'            => 'Supplier',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 11,
                'level'              => 8,
                'org'                => false,
                'for_org'            => 11,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '注册客户',
                'name_en'            => 'register customer',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 10,
                'level'              => 8,
                'org'                => false,
                'for_org'            => 10,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '句容建设路1店',
                'name_en'            => 'ONE CITY(JIANGYIN)',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 10,
                'level'              => 8,
                'org'                => false,
                'for_org'            => 10,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '句容人民路',
                'name_en'            => 'ZHANGJIAGANG',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 12,
                'level'              => 8,
                'org'                => false,
                'for_org'            => 12,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '茅山水库',
                'name_en'            => 'Lulu',
                'extra'              => '',
                'created_by'          => 1
            ],[
                'parent_id'          => 12,
                'level'              => 8,
                'org'                => false,
                'for_org'            => 12,
                'independent'        => false,
                'allow_root'         => true,
                'allow_admin'        => true,
                'allow_master'       => true,
                'is_shop'            => false,
                'is_supplier'        => false,
                'is_customer'        => false,
                'hide'               => false,
                'order'              => 3,
                'name'               => '瓜子沟果园',
                'name_en'            => 'LiuZhanTai',
                'extra'              => '',
                'created_by'          => 1
            ],
        ]);
    }
}

















