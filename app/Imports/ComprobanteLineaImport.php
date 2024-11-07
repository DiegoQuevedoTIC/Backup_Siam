<?php

namespace App\Imports;

use App\Models\ComprobanteLinea;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ComprobanteLineaImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts
{
    public $comprobante_id;
    public $linea = 0;
    public $totalDebito = 0;
    public $totalCredito = 0;


    public function __construct($comprobante_id)
    {
        $this->comprobante_id = $comprobante_id;

        $lastLine = DB::table('comprobantes as c')
            ->join('comprobante_lineas as cl', 'c.id', 'cl.comprobante_id')
            ->where('c.id', $this->comprobante_id)
            ->orderBy('cl.linea', 'desc')
            ->value('cl.linea');

        $this->linea = $lastLine ? $lastLine + 1 : 1;
    }

    public function model(array $row)
    {
        // Acumula los totales
        $this->totalDebito += $row['debito'];
        $this->totalCredito += $row['credito'];

        $comprobanteLinea = new ComprobanteLinea([
            'pucs_id'         => DB::table('pucs')->where('puc', $row['puc'])->first()->id,
            'tercero_id'      => DB::table('terceros')->where('tercero_id', $row['tercero'])->first()->id,
            'descripcion_linea' => $row['descripcion'],
            'debito'          => $row['debito'],
            'credito'         => $row['credito'],
            'comprobante_id'  => $this->comprobante_id,
            'linea'          => $this->linea,
        ]);

        $this->linea++; // Incrementa para la próxima línea

        return $comprobanteLinea;
    }

    public function onAfterImport()
    {
        // Validar que las sumas sean iguales
        if ($this->totalDebito !== $this->totalCredito) {
            throw new \Exception('La suma de los débitos y créditos no es igual. No se puede cargar el archivo.');
        }
    }

    public function rules(): array
    {
        return [
            'puc' => [
                'required',
                'exists:pucs',
            ],
            'tercero' => [
                'required',
                'exists:terceros,tercero_id',
            ],
            'descripcion' => [
                'required',
                'string',
                'max:255',
            ],
            'debito' => [
                'min:0',
                'numeric'
            ],
            'credito' => [
                'min:0',
                'numeric'
            ],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'puc.exists' => 'El PUC no existe en la base de datos.',
            'tercero.required' => 'El tercero es requerido.',
            'tercero.exists' => 'El tercero no existe en la base de datos.',
            'descripcion.required' => 'La descripción es requerida.',
            'descripcion.string' => 'La descripción debe ser una cadena de caracteres.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
            'debito.required' => 'El débito es requerido.',
            'debito.numeric' => 'El débito debe ser un número.',
            'debito.min' => 'El débito debe ser mayor o igual a 0.',
            'credito.required' => 'El crédito es requerido.',
            'credito.numeric' => 'El crédito debe ser un número.',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
