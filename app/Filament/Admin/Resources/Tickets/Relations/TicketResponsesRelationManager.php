<?php

namespace App\Filament\Admin\Resources\Tickets\Relations;

use App\Models\TicketResponse;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Response')
                    ->schema([
                        Textarea::make('message')
                            ->required()
                            ->rows(4),

                        Toggle::make('is_internal')
                            ->label('Internal Note')
                            ->default(false),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Responder'),

                TextColumn::make('message')
                    ->limit(50),

                TextColumn::make('is_internal')
                    ->label('From Admin')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['is_internal'] = true;
                        return $data;
                    })
                    ->after(function () {
                        $this->ownerRecord->update(['allow_user_reply' => true]);
                    }),
            ]);
    }
}