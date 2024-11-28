<?php

namespace App\Imports;

use App\Models\SaldoPuc;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SaldoPucImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if ($row['agencia'] === 1) {
            return new SaldoPuc([
                'puc'  => $row['puc'],
                'amo' => $row['amo'],
                'mes' => $row['mes'],
                'saldo_debito' => $row['saldo_debito'] ?? 0,
                'saldo_credito' => $row['saldo_credito'] ?? 0,
                'saldo' => $row['saldo'] ?? 0,
            ]);
        }

        if ($row['agencia'] === 2) {

            $cuenta = SaldoPuc::where('puc', $row['puc'])->where('amo', $row['amo'])->where('mes', $row['mes'])->first();

            if ($cuenta) {

                $cuenta->saldo_debito += $row['saldo_debito'] ?? 0;
                $cuenta->saldo_credito += $row['saldo_credito'] ?? 0;
                $cuenta->saldo += $row['saldo'] ?? 0;
                $cuenta->save();

                return $cuenta;
            }


            return null;
        }

        return null;
    }
}
