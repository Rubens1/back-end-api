<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedidos; // Adjust the namespace as needed

class PedidosSeeder extends Seeder
{
    public function run()
    {
        \DB::table('pedidos')->insert($this->getData());
    }

    private function getData()
    {
        $data = [];

        // Generate fake data for the pedidos table
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'is_active' => rand(0, 1),
                'id_pedido' => rand(1, 100),
                'id_contrato' => rand(1, 100),
                'id_bureau' => rand(1, 100),
                'tipo' => ['PEDIDO', 'ORCAMENTO', 'FATURA', 'AGRUPAMENTO', 'NF-ENTRADA', 'NF-DEVOLUCAO'][array_rand(['PEDIDO', 'ORCAMENTO', 'FATURA', 'AGRUPAMENTO', 'NF-ENTRADA', 'NF-DEVOLUCAO'])],
                'custo_total' => number_format(rand(1, 10000) / 100, 2),
                'custo_frete' => number_format(rand(1, 10000) / 100, 2),
                'sit_pagto' => ['FATURADO', 'AG. PAGTO', 'PAGO', 'VENCIDO', 'CANCELADO', 'EM ATRASO'][array_rand(['FATURADO', 'AG. PAGTO', 'PAGO', 'VENCIDO', 'CANCELADO', 'EM ATRASO'])],
                'sit_pedido' => ['ABERTO', 'FECHANDO', 'EM COTACAO', 'FECHADO', 'APROVADO', 'CANCELADO', 'FINALIZADO', 'CONSOLIDADO', 'RECUSADO', 'REPROVADO', 'FATURADO'][array_rand(['ABERTO', 'FECHANDO', 'EM COTACAO', 'FECHADO', 'APROVADO', 'CANCELADO', 'FINALIZADO', 'CONSOLIDADO', 'RECUSADO', 'REPROVADO', 'FATURADO'])],
                'sit_entrega' => ['AG. LIBERACAO', 'LIBERADO', 'REGISTRADO', 'EM TRANSITO', 'ENTREGUE', 'RETORNOU', 'NOVA TENTATIVA', 'DISP. RETIRADA', 'AGUARDANDO COLETA/TRANSPORTE'][array_rand(['AG. LIBERACAO', 'LIBERADO', 'REGISTRADO', 'EM TRANSITO', 'ENTREGUE', 'RETORNOU', 'NOVA TENTATIVA', 'DISP. RETIRADA', 'AGUARDANDO COLETA/TRANSPORTE'])],
                'sit_producao' => ['AG. LIBERACAO', 'LIBERADO', 'IMPRIMINDO', 'EM PAUSA', 'FINALIZADO'][array_rand(['AG. LIBERACAO', 'LIBERADO', 'IMPRIMINDO', 'EM PAUSA', 'FINALIZADO'])],
                'vencimento' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'data_pagto' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'datahora' => now()->format('Y-m-d H:i:s'),
                'validade_proposta' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'prazo_producao' => rand(1, 30) . ' days',
                'comissoes_pagas' => rand(0, 1),
                'obs' => $this->getRandomText(),
                'file_cotacao' => 'cotacao_file_' . rand(1, 100) . '.pdf',
                'file_pedido' => 'pedido_file_' . rand(1, 100) . '.pdf',
                'royalties_pagos' => rand(0, 1),
                'priority' => ['NORMAL', 'BAIXA', 'ALTA', 'URGENTE'][array_rand(['NORMAL', 'BAIXA', 'ALTA', 'URGENTE'])],
                'created_at' => now(),
                'updated_at' => now(),
                'id_cliente' => 1,
                'id_op_frete' => 1,
                'id_op_pagto' => 1,
                'id_endereco' => 1,
                'id_pessoa' => 1,
                'id_vendedor' => 1,
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
