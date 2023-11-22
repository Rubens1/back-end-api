<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\ActionsHistory;
use Illuminate\Http\Request;
use App\Helpers\Logger;
use Illuminate\Support\Facades\{Hash, Validator};


class LoggerController extends Controller
{
    public $pessoa;
    public function __construct()
    {
        $this->pessoa = auth('pessoas')->user();
    }

    public function obterLogs(Request $request, $id)
    {

        $logs = ActionsHistory::where("id_pessoa", $id)
            ->orderBy("created_at", "DESC")
            ->get();

        if ($logs->count() == 0) {
            return response()->json([
                "status" => "error",
                "message" => "Log não econtrado"
            ], 422);
        }

        return response()->json($logs);
    }

    public function criarLog(Request $request)
    {

        $validator = validator::make($request->only("obs"), [
            "obs" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 422);
        }
    }

}