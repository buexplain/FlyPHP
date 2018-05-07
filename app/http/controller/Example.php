<?php namespace app\http\controller;

use Cache;
use App;
use Config;
use Request;
use Route;
use Response;
use Redis;
use Session;
use Log;
use DB;

class Example
{
    public function index()
    {
        Route::getController();
        Request::file('x');
        Config::get('xx');
        App::get('xx');
        Cache::get('xx');
        Redis::set('x', 0);

        Log::warning('xxxxx', 'a');

        $id = 0;
        $id = db()->select()->table('user')->where('user_id', 2)->execute()->pluck('user_id');

        return Response::view('Example.index')->with('msg', $id);
    }

    public function ajax()
    {
        Cache::del('x');
        Cache::has('x');
        return view('Example.ajax');
    }

    public function upload()
    {
        dd(\fly\http\UploadFile::getMaxFileSize());
    }
}