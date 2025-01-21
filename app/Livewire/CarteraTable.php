<?php

namespace App\Livewire;

use App\Models\Tercero;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;

class CarteraTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $show = true;

    public function table(Table $table): Table
    {
        return $table
            ->query(Tercero::query())
            ->columns([
                TextColumn::make('nombres'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.cartera-table');
    }
}
