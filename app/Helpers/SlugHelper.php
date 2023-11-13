<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\{
    Produtos,
    Estoque,
    Actions,
    ActionsHistory
};
use Exception;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};

trait SlugHelper
{
    public function generateSlug($str)
    {
        $str = mb_strtolower($str);
        $str = preg_replace('/(â|á|ã)/', 'a', $str);
        $str = preg_replace('/(ê|é)/', 'e', $str);
        $str = preg_replace('/(í|Í)/', 'i', $str);
        $str = preg_replace('/(ú)/', 'u', $str);
        $str = preg_replace('/(ó|ô|õ|Ô)/', 'o', $str);
        $str = preg_replace('/(_|\/|!|\?|#)/', '', $str);
        $str = preg_replace('/( )/', '-', $str);
        $str = preg_replace('/ç/', 'c', $str);
        $str = preg_replace('/(-[-]{1,})/', '-', $str);
        $str = preg_replace('/(,)/', '-', $str);
        $str = strtolower($str);
        return $str;
    }

}