<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionsHistory extends Model
{
    use HasFactory;

    protected $table = 'actions_history';

    // Define the relationships with other tabl
    protected $fillable = [
        "obs",
        "id_pedido",
        "id_cliente",
        "id_agente",
        "id_pessoa",
        "id_produto",
        "ip"
    ];
    public function actions()
    {
        return $this->belongsTo(Actions::class, 'id_action');
    }

    public function pedidos()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }

    public function agentes()
    {
        return $this->belongsTo(Pessoas::class, 'id_agente');
    }

    public function pessoas()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }

    public function produtos()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }
}
