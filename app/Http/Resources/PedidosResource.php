<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "is_active" => $this->is_active,
            "custo_total" => $this->custo_total,
            "vencimento" => $this->vencimento,
            "sit_pedido" => $this->sit_pedido,
            "data_pagamento" => $this->data_pagamento,
            "sit_entrega" => $this->sit_entrega,
            "nome_cliente" => $this->cliente->nome,
            "vendedor" => $this->vendedor->nome,

        ];
    }
}
