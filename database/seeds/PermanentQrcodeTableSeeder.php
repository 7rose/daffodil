<?php

use Illuminate\Database\Seeder;

class PermanentQrcodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wechat_permanent_qrcode')->insert([
            [
                'staff_id' => 2,
                'label'    => 'web',
                'ticket'   => 'gQF78TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyS3JZU2NMZTBlWWsxMDAwMHcwN0EAAgQSzo9ZAwQAAAAA',
                'url'      => 'http://weixin.qq.com/q/02KrYScLe0eYk10000w07A',
            ],
        ]);
    }
}
