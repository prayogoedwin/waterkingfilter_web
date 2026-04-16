<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Models\Partner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()
                    ->email()
                    ->unique('partners', 'email'),
                Select::make('settlement_method')
                    ->label('Metode Settlement')
                    ->options(Partner::settlementMethodOptions())
                    ->default(Partner::SETTLEMENT_POSTPAID)
                    ->required()
                    ->helperText('Postpaid: pencairan di akhir. Prepaid: gunakan modal/topup terlebih dahulu.'),
                Textarea::make('alamat')->required(),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->required()
                    ->directory('partners')
                    ->visibility('public')
                    ->required(fn(string $context): bool => $context === 'create'),
            ]);
    }
}
