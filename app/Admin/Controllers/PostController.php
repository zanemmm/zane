<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\{
    PublishPost, RestorePost, Trashed, ZaneDeleteTrash, ZaneMoveTrash, ZaneEmptyTrash
};
use App\Admin\Extensions\ZaneParsedown;
use App\Models\{Category,Post,Tag};
use Encore\Admin\{Form,Grid};
use Encore\Admin\Grid\Tools\BatchActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Overtrue\Pinyin\Pinyin;

class PostController extends Controller
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
            $content->header('文章列表');
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
            $content->header('修改文章');
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
            $content->header('新增文章');
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
        return Admin::grid(Post::class, function (Grid $grid) {
            $grid->model()->orderBy('created_at', 'desc');
            if (request('trashed') == 1) {
                $grid->model()->onlyTrashed();
            }

            $grid->title('文章标题')->sortable();
            $grid->category('文章分类')->display(function ($category) {
                return $category['name'] ?? '暂无分类';
            });
            $grid->tags('文章标签')->pluck('name')->label();
            $grid->publish('文章状态')->display(function ($state) {
                if ($state) {
                    return "<span class=\"label label-success\">公开</span>";
                }
                return "<span class=\"label label-danger\">私密</span>";
            })->sortable();
            $grid->created_at('创建时间')->sortable();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('title', '文章标题');
            });

            $grid->actions(function ($actions) {
                if (request('trashed') == 1) {
                    $actions->disableDelete();
                }
            });
            $grid->tools(function ($tools) {
                $tools->append(new Trashed());
                $tools->batch(function (BatchActions $batch) {
                    if (request('trashed') == 1) {
                        $batch->add('从回收站恢复', new RestorePost());
                        $batch->add('永久删除文章', new ZaneDeleteTrash());
                        $batch->add('清空回收站', new ZaneEmptyTrash());
                    } else {
                        $batch->add('移入回收站', new ZaneMoveTrash());
                        $batch->add('设为公开', new PublishPost(1));
                        $batch->add('设为私密', new PublishPost(0));
                    }
                    $batch->disableDelete();
                });
            });
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Post::class, function (Form $form) {
            $form->tab('文章信息', function (Form $form) {
                $form->hidden('id');
                $form->text('title', '标题')
                    ->rules('required');
                $form->select('category_id', '分类')
                    ->options(Category::query()->pluck('name', 'id'));
                $form->markdown('content','内容');
                $form->hidden('html');
                $form->hidden('summary');
                $form->zaneTags('tags', '标签')
                    ->options(Tag::all()->pluck('name','id'));
                $states = [
                    'on'  => ['text' => '公开', 'color' => 'success'],
                    'off' => ['text' => '私密', 'color' => 'warning'],
                ];
                $form->switch('publish', '状态')->states($states);
                $form->display('created_at', '创建时间');
                $form->display('updated_at', '更新时间');
            })->tab('SEO信息', function (Form $form) {
                $form->text('slug', "别名");
                $form->tags('keywords', "关键字");
                $form->textarea('description', "简介");
            });
            $form->disableReset();
            $form->saving(function (Form $form) {
                //填充数据
                $parseDown = new ZaneParsedown();
                $form->input('html', $parseDown->text($form->input('content')));
                $form->input('summary', Post::getSummary($form->input('html'), 300));
                if (empty($form->input('slug'))) {
                    $pinyin = new Pinyin();
                    $slug = implode('-', $pinyin->convert($form->title));
                    $form->input('slug', $slug);
                }
                if (empty($form->input('description'))) {
                    $form->input('description', Post::getDescription( $form->input('summary')));
                }
                //验证 slug 唯一
                $post = Post::query()->where('slug', $form->input('slug'))->first();
                if ($post) {
                    $model = $form->model();
                    if ($model && ($model->id === $post->id)) {
                        return;
                    }
                    $error = new MessageBag([
                        'title'   => '链接别名已重复,重新输入!',
                        'message' => 'SEO信息中的别名与其它文章重复请输入一个新的别名.',
                    ]);
                    return back()->with(compact('error'));
                }
            });
            $form->saved(function (Form $form) {
                //tags to keywords
                $post = $form->model();
                if (empty($post->keywords)) {
                    $tags = $post->tags()->get()->pluck('name', 'id')->toArray();
                    $post->keywords = implode(',', $tags);
                    $post->save();
                }
            });
        });
    }

    public function publish(Request $request)
    {
        foreach (Post::find($request->get('ids')) as $post) {
            $post->publish = $request->get('action');
            $post->save();
        }
    }

    public function restore(Request $request)
    {
        return Post::onlyTrashed()->find($request->get('ids'))->each->restore();
    }

    public function emptyTrash()
    {
        $posts = Post::onlyTrashed()->get()->each->forceDelete();

        if (!empty($posts)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request('force', 0)) {
            $ids = explode(',', $id);
            Post::onlyTrashed()->find($ids)->each->forceDelete();
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        }

        if ($this->form()->destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }
}
