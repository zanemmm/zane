<?php

namespace App\Admin\Extensions;

use Overtrue\Pinyin\Pinyin;
use Parsedown;

class ZaneParsedown extends Parsedown {

    protected function blockHeader($Line)
    {
        $header = parent::blockHeader($Line);
        if (empty($header)) {
            return null;
        }

        $pinyin = new Pinyin();
        $text   = $header['element']['text'];
        $id     = implode('-', $pinyin->convert($text));
        $header['element']['attributes']['id'] = $id;

        return $header;
    }

    protected function blockFencedCode($Line)
    {
        $code = parent::blockFencedCode($Line);
        if (isset($code['element']['text']['attributes']['class'])) {
            $class = explode('-', $code['element']['text']['attributes']['class']);
            $code['element']['text']['attributes']['class'] = $class[1] ?? null;
        }
        return $code;
    }
}