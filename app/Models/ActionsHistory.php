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
    public function action()
    {
        return $this->belongsTo(Action::class, 'id_action');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function agente()
    {
        return $this->belongsTo(Pessoa::class, 'id_agente');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'id_pessoa');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
