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

trait Logger
{

    public $pessoa;

    public function __construct()
    {

        $this->pessoa = auth('pessoas')->user();
    }

    public function info(Request $request): void
    {
        $info = [
            "id_pessoa" => $this->pessoa->id ?? null,
            "obs" => $request->obs,
            "id_produto" => $request->produto_id ?? null,
            "id_agente" => $request->id_agente ?? null,
            "ip" => $request->ip(),
        ];

        $action = Actions::create([
            "action" => $info["obs"]
        ])->fresh();

        $merged = [...$info, "id_action" => $action->id];

        ActionsHistory::create($merged);
    }
}