<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class Trashed extends AbstractTool
{
    protected function script()
    {
        $url  = Request::fullUrlWithQuery(['trashed' => '_trashed_']);

        return <<<EOT

$('.grid-status').click(function () {
    var status = $(this).find('input')[0].checked ? 0 : 1;
    $.pjax({container:'#pjax-container', url: "$url".replace('_trashed_', status) });
});

EOT;

    }

    public function render()
    {
        Admin::script($this->script());

        $checked = (Request::get('trashed') == 1) ? 'checked' : '';
        $name = (Request::get('trashed') == 1) ? '文章列表' : '回收站';
        $icon = (Request::get('trashed') == 1) ? 'fa-th-list' : 'fa-trash';
        $btn = (Request::get('trashed') == 1) ? 'btn-primary' : 'btn-danger';

        return <<<EOT

<div class="btn-group" data-toggle="buttons">
    <label class="btn $btn btn-sm grid-status">
        <input type="checkbox" $checked /> <i class="fa $icon"></i> $name
    </label>
</div>

EOT;
    }
}