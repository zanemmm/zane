<?php


namespace App\Admin\Extensions\Tools;


use Encore\Admin\Grid\Tools\BatchAction;

class ZaneMoveTrash extends BatchAction
{

    /**
     * Script of batch delete action.
     */
    public function script()
    {
        $deleteConfirm = '确认将所选项移入回收站?';
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<EOT

$('{$this->getElementClass()}').on('click', function() {

    var id = selectedRows().join();

    swal({
      title: "$deleteConfirm",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: '{$this->resource}/' + id,
            data: {
                _method:'delete',
                _token:'{$this->getToken()}'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

EOT;
    }
}