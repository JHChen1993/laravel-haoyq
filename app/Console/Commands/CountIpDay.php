<?php

namespace App\Console\Commands;

use App\Mail\SendSystemInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class CountIpDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countIp:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计每日访问 IP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $redis = Redis::connection('cache');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $redisKey = 'user_ip:' . $yesterday;

        $data = $yesterday . ' 访问 IP 总数为 ' . $redis->scard($redisKey);

        // 发送邮件
        Mail::to(env('ADMIN_EMAIL'))->send(new SendSystemInfo($data));
    }
}
