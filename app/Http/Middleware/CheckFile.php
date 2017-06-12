<?php

namespace App\Http\Middleware;

use Closure;

class CheckFile
{
    /**
     * 上传文件验证中间件
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
