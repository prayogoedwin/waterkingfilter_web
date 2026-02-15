<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(
                        table: 'members',
                        column: 'email',
                        ignoreRecord: true
                    ),
                TextInput::make('password')
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->confirmed()
                    ->rules(['nullable'])
                    ->dehydrated(fn($state) => filled($state))
                    ->autocomplete('new-password')
                    ->prefixIcon('heroicon-o-lock-closed')
                    ->placeholder(
                        fn(string $operation): string =>
                        $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : ''
                    ),
                TextInput::make('password_confirmation')
                    ->requiredWith('password')
                    ->password()
                    ->revealable()
                    ->label('Confirm Password')
                    ->dehydrated(false)
                    ->placeholder(
                        fn(string $operation): string =>
                        $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : ''
                    ),
                TextInput::make('whatsapp')
                    ->label('No Whatsapp')
                    ->default(0),
                Toggle::make('status')
                    ->label('Status'),
            ]);
    }
}
