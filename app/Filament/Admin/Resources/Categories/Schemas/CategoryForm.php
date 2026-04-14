<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit')
                    ->helperText('Auto-generated from name.'),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('assigned_to')
                    ->label('Default assigned staff')
                    ->options(
                        User::whereIn('role', ['staff', 'admin'])
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}