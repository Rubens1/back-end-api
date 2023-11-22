<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Grupos;
use Illuminate\Support\Facades\{Hash, Validator, DB};

class PermissaoController extends Controller
{
    public function listAllRoles()
    {
        return response()->json(
            Grupos::select("grupo as label", "id as value")->get());
    }
    public function createRole(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                "grupo" => "unique:grupos|required|string|max:100",
                
            ]);

            if($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "errors" => $validator->errors()
                ]);
            }

            $permissoes = implode(" ", $request->permissoes);

            Grupos::create([
                "grupo" => $request->grupo,
                "permissoes" => $permissoes
            ]);
            
            return response()->json([
                "message" => "Grupo criado"
            ]);

        }catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
}
