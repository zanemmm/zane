<?php

namespace App\Http\Services;

use phpDocumentor\Reflection\Types\Self_;
use Throwable;

class FrontendRenderService
{
    protected $data = [];

    public function main(string $component, array $data = []): self
    {
        try {
            $main = view('components.' . $component, $data)->render();
        } catch (Throwable $e) {
            //TODO log something
            $main = [];
        }

        $this->data = array_merge($this->data, compact('main'));

        return $this;
    }

    public function title(string  $title): self
    {
        $this->data = array_merge($this->data, compact('title'));

        return $this;
    }

    public function get(): array
    {
        $this->navbar();
        $this->breadcrumb();
        if (empty($this->data['title'])) {
            $this->title(view()->shared('title', config('site.title', "Zane's Blog")));
        }

        return $this->data;
    }

    public function __toString()
    {
        return json_encode($this->get());
    }

    public function __call($name, $params = [])
    {
        if (empty($params)) {
            $component = $name;
            $data = [];
        } else {
            $component = $params[0];
            $data = $params[1] ?? [];
        }

        try {
            $html = view('components.' . $component, $data)->render();
        } catch (Throwable $e) {
            //TODO log something
            $html = [];
        }

        $this->data = array_merge($this->data, [$name => $html]);

        return $this;
    }
}