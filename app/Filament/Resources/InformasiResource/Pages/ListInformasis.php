<?php

namespace App\Filament\Resources\InformasiResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\InformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformasis extends ListRecords
{
    protected static string $resource = InformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
