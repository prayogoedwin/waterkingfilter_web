<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\FileUpload;
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
