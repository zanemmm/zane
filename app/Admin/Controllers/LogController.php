<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\ZaneEmptyLogs;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid\Tools\BatchActions;
use Encore\Admin\Controllers\LogController as Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\OperationLog;

class LogController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.operation_log'));
            $content->description(trans('admin.list'));

            $grid = Admin::grid(OperationLog::class, function (Grid $grid) {
                $grid->model()->orderBy('id', 'DESC');

                $grid->id('ID')->sortable();
                $grid->user()->name('User');
                $grid->method()->display(function ($method) {
                    $color = array_get(OperationLog::$methodColors, $method, 'grey');

                    return "<span class=\"badge bg-$color\">$method</span>";
                });
                $grid->path()->label('info');
                $grid->ip()->label('primary');
                $grid->input()->display(function ($input) {
                    $input = json_decode($input, true);
                    $input = array_except($input, ['_pjax', '_token', '_method', '_previous_']);
                    if (empty($input)) {
                        return '<code>{}</code>';
                    }

                    return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
                });

                $grid->created_at(trans('admin.created_at'));

                $grid->tools(function ($tools) {
                    $tools->batch(function (BatchActions $batch) {
                        $batch->add('清空日志', new ZaneEmptyLogs());
                    });
                });

                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableEdit();
                });

                $grid->disableCreateButton();

                $grid->filter(function ($filter) {
                    $filter->equal('user_id', 'User')->select(Administrator::all()->pluck('name', 'id'));
                    $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
                    $filter->like('path');
                    $filter->equal('ip');
                });
            });

            $content->body($grid);
        });
    }

    public function empty()
    {
        OperationLog::truncate();
        return response()->json([
            'status'  => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}
