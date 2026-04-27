<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(function ($state) {
                        return filled($state) ? bcrypt($state) : null;
                    })
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($context) => $context === 'create'),
                TextInput::make('nim'),
                Select::make('role')
                    ->options([
                        'public' => 'Public',
                        'student' => 'Student',
                        'staff' => 'Staff',
                        'admin' => 'Admin',
                        'super_admin' => 'Super Admin',
                    ])
                    ->required()
                    ->default('public'),
            ]);
    }
}
