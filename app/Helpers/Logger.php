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

class Logger
{
    public static function info(array $info = []) //: void
    {
        $action = Actions::create([
            "action" => $info["obs"]
        ])->fresh();

        $merged = [...$info, "id_action" => $action->id];

        ActionsHistory::create($merged);
    }
}
