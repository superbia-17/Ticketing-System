<?php

namespace App\Filament\Admin\Resources\Tickets\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket Info')
                    ->schema([
                        TextInput::make('ticket_number')
                            ->disabled()
                            ->visibleOn('edit'),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Reporter')
                    ->schema([
                        TextInput::make('reporter_name')
                            ->label('Submitted by')
                            ->disabled()
                            ->visibleOn('edit'),

                        TextInput::make('reporter_email')
                            ->label('Email')
                            ->disabled()
                            ->visibleOn('edit'),

                        TextInput::make('reporter_nim')
                            ->label('NIM')
                            ->disabled()
                            ->visibleOn('edit'),
                    ])
                    ->columns(3)
                    ->visibleOn('edit'),

                Section::make('Management')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'open'        => 'Open',
                                'in_progress' => 'In Progress',
                                'resolved'    => 'Resolved',
                                'closed'      => 'Closed',
                            ])
                            ->required()
                            ->default('open'),

                        Select::make('priority')
                            ->options([
                                'low'    => 'Low',
                                'medium' => 'Medium',
                                'high'   => 'High',
                            ])
                            ->required()
                            ->default('medium'),

                        Select::make('assigned_to')
                            ->label('Assign to')
                            ->options(
                                User::whereIn('role', ['staff', 'admin', 'super_admin'])
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->nullable(),

                        Toggle::make('allow_user_reply')
                            ->label('Allow User to Reply')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}