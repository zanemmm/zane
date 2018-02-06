<?php

namespace App\Admin\Controllers;

use App\Models\Tag;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Overtrue\Pinyin\Pinyin;

class TagController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('标签管理');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('修改标签');
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('新增标签');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Tag::class, function (Grid $grid) {
            $grid->name('标签名称')->sortable();
            $grid->slug('标签别名')->sortable();
            $grid->posts("文章数量")->display(function ($posts) {
                return count($posts);
            })->sortable();
            $grid->created_at('创建时间')->sortable();
            $grid->updated_at('更新时间')->sortable();

            $grid->disableExport();
            $grid->disableFilter();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Tag::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', '名称');
            $form->text('title', "标题");
            $form->text('slug', '别名');
            $form->tags('keywords', "关键字");
            $form->textarea('description', "简介");
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');

            $form->saving(function (Form $form) {
                if (empty($form->input('slug'))) {
                    $pinyin = new Pinyin();
                    $form->input('slug', implode('-', $pinyin->convert($form->name)));
                }
            });
        });
    }
}
