<?php

namespace App\Service;

class Str
{
    public function _token($length = 60)
    {
        return substr(str_shuffle(str_repeat("azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789",$length )),0,$length );
    }
}