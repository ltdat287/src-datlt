<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();
        DB::table('users')->insert([
            'name' => '管理 太郎',
            'email' => 'admin@localhost',
            'password' => bcrypt('SxRVYMtn'),
            'kana' => 'カンリ タロウ',
            'telephone_no' => '090-1234-5678',
            'birthday' => Carbon::create(1980, 1, 1),
            'note' => "EDU EMS デフォルトアカウントです。\nこのアカウントは常に存在します。",
			'role' => ADMIN,
			'created_at' => Carbon::create(2015, 1, 4, 11, 12, 13),
			'updated_at' => Carbon::create(2015, 1, 4, 11, 12, 13),
        ]);
    }
}
