<style>
.editor-toolbar.fullscreen {
  z-index: 9999;
}
.CodeMirror-fullscreen {
  z-index: 9999;
}
.CodeMirror {
    height: 600px;
}
</style>

<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <textarea class="form-control {{ $class }}" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>

<script>

</script>