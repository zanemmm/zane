<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\FrontendRenderService as Render;


class VerifyKeyword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $keyword = (string) $request->route()->parameter('keyword');
        $validator = Validator::make(compact('keyword'), [
            'keyword' => 'required|between:3,12|regex:/^[a-zA-Z0-9\x{4e00}-\x{9fa5}\s]+$/u'
        ]);
        if ($validator->fails()) {
            $title  = '搜索出现问题 - ' . config('site.name');
            $status = '搜索关键词不合法!';
            $message = '关键词长度必须在3到12个字符之内,并且不包括符号.';
            if ($request->method() === 'GET') {
                return response()
                    ->view('error', compact('title', 'status', 'message'))
                    ->setStatusCode(422);
            }
            $render = new Render();
            $data = $render->title($title)
                ->main('error', compact('status', 'message'));
            return response($data);
        }
        return $next($request);
    }
}
