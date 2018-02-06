<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class SimpleMDE extends Field
{
    protected $view = 'admin.markdown-editor';

    protected static $css = [
        'https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css',
    ];

    protected static $js = [
        'https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js',
    ];

    public function render()
    {
        $prefix = config('admin.route.prefix');
        $token = csrf_token();
        $imageUrl = url('storage/admin/posts/images');
        $this->script = <<<EOT
$('#pjax-container').append(
"<form action=\"/$prefix/media/upload\" method=\"post\" id=\"uploadForm\" enctype=\"multipart/form-data\">" +
"    <input type=\"file\" name=\"files[]\" id=\"zaneUpload\"  style=\"display: none\" multiple>" +
"    <input type=\"hidden\" name=\"dir\" value=\"/admin/posts/images\" />" +
"    <input type=\"hidden\" name=\"_token\" value=\"$token\">"+
"</form>"
);

$('#zaneUpload').on('change', upload);
$("#uploadForm").submit(function(e){
    // prevent the form from submitting
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        contentType:false,
        processData: false,
        data: new FormData(this),
        success: function(result) {

        },
        error: function(xhr, result, errorThrown){
            console.log(result);
            alert('上传失败!');
        }
    });
});
function upload() {
    var defaultImage = simplemde.options.insertTexts.image;
    var files = $('#zaneUpload')[0].files;
    for (var i=0; i < files.length; i++) {
        if (i === 0) {
            simplemde.options.insertTexts.image = ["!["+ files[i].name +"]($imageUrl/"+ files[i].name +")", ''];
        } else {
            simplemde.options.insertTexts.image = ["\\n\\n!["+ files[i].name +"]($imageUrl/"+ files[i].name +")", ''];
        } 
        $('[title="Insert Image (Ctrl-Alt-I)"]').click();
    }
    simplemde.options.insertTexts.image = defaultImage;
    $('#uploadForm').submit();
}
var uniqueId = $("[name=id]").val() || "new";
var simplemde = new SimpleMDE({
    element: document.getElementById("{$this->id}"),
    autoDownloadFontAwesome: false,
    spellChecker: false,
    autosave: {
		enabled: true,
		uniqueId: uniqueId,
		delay: 1000,
	},
	insertTexts: {
	    image: ["![]($imageUrl/", ")"]
	},
	toolbar: [
	"bold",
	"italic",
	"strikethrough",
	"|",
	"heading",
	"code",
	"quote",
	"|",
	"unordered-list",
	"ordered-list",
	"|",
	"link",
	"image",
    {
        name: "upload",
        action: function (editor) {
            $('#zaneUpload').click();
        },
        className: "fa fa-upload",
        title: "Upload Images",
    },
	"|",
	"preview",
	"side-by-side",
	"fullscreen",
	],
});
EOT;
        return parent::render();
    }
}