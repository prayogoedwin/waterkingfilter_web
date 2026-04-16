<?php

namespace App\Filament\Resources\WhatsappBroadcastHistoryResource\Pages;

use App\Filament\Resources\WhatsappBroadcastHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWhatsappBroadcastHistories extends ListRecords
{
    protected static string $resource = WhatsappBroadcastHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Kirim Broadcast'),
        ];
    }
}

