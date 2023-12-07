<?php

namespace Database\Seeders;

// database/seeders/PedidosItensSeeder.php

use Illuminate\Database\Seeder;
use App\Models\PedidosItens;

class PedidosItensSeeder extends Seeder
{
    public function run()
    {
        \DB::table('pedidos_itens')->insert($this->getData());
    }

    private function getData()
    {
        $data = [];

        // Generate fake data for the pedidos_itens table
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                //'situacao' => "PENDENTE",
                'item_data' => $this->getRandomText(),
                'qtd' => rand(1, 10),
                'total' => number_format(rand(1, 100) / 100, 2),
                'preco' => number_format(rand(1, 100) / 100, 2),
                'valor_royalties' => number_format(rand(1, 100) / 100, 2),
                'perc_royalties' => rand(1, 100) / 100,
                'royalties_pagos' => rand(0, 1),
                'perc_comissao' => rand(1, 100) / 100,
                'tipo_cartao' => 'Tipo' . rand(1, 10),
                'coresFrente' => rand(1, 5),
                'coresVerso' => rand(1, 5),
                'custoProducao' => number_format(rand(1, 100) / 100, 2),
                'repasseServico' => number_format(rand(1, 100) / 100, 2),
                'repasseManutencao' => number_format(rand(1, 100) / 100, 2),
                'custoColor' => number_format(rand(1, 100) / 100, 2),
                'id_produto_estoque' => rand(1, 100),
                'preco_compra' => number_format(rand(1, 100) / 100, 2),
                'valor_comissao' => number_format(rand(1, 100) / 100, 2),
                'creditado' => rand(0, 1),
                'bx_estoque' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
                'id_produto' => 1,
                'id_pedido' => 39,
            ];
        }

        return $data;
    }

    private function getRandomText()
    {
        $texts = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
        ];

        return $texts[array_rand($texts)];
    }
}

