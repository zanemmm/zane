<?php

namespace App\Admin\Extensions;

use App\Models\Tag;
use Encore\Admin\Form\Field;
use Overtrue\Pinyin\Pinyin;

class ZaneTags extends Field
{
    /**
     * @var array
     */
    protected $value = [];

    /**
     * @var array
     */
    protected static $css = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css',
    ];

    /**
     * @var array
     */
    protected static $js = [
        '/vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js',
    ];

    protected $view = 'admin.zane-tags';

    public function fill($data)
    {
        if (is_array($this->column)) {
            foreach ($this->column as $key => $column) {
                $this->value[$key] = $this->formatValue(array_get($data, $column));
            }

            return;
        }

        $this->value = $this->formatValue(array_get($data, $this->column));
    }

    protected function formatValue($value) {
        $result = [];
        foreach ($value as $select) {
            $result[$select['id']] = $select['name'];
        }

        return $result;
    }

    public function options($options = [])
    {
        $this->options = $options;

        return $this;
    }

    public function prepare($value)
    {
        $allTags = Tag::all()->pluck('name','id')->toArray();
        foreach ($value as $k => $v) {
            if (empty($v)) {
                unset($value[$k]);
                continue;
            }
            if (!key_exists($v, $allTags)) {
                $pinyin = new Pinyin();
                $tag = Tag::create([
                    'name' => $v,
                    'slug' => implode('-', $pinyin->convert($v))
                ]);
                $value[$k] = $tag->id;
            }
        }
        return $value;
    }

    /**
     * Get or set value for this field.
     *
     * @param mixed $value
     *
     * @return $this|array|mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return empty($this->value) ? ($this->getDefault() ?? []) : $this->value;
        }

        $this->value = $value;

        return $this;
    }

    public function render()
    {
        $this->script = "$(\"{$this->getElementClassSelector()}\").select2({
            tags: true,
            tokenSeparators: [',']
        });";

        return parent::render()->with(['options' => $this->options]);
    }
}