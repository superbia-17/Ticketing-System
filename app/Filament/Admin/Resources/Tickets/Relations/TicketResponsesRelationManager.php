<?php

namespace App\Filament\Admin\Resources\Tickets\Relations;

use App\Models\TicketResponse;
use App\Notifications\TicketRepliedNotification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
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
                            ->rows(4),

                        FileUpload::make('image')
                            ->label('Lampiran Gambar')
                            ->image()
                            ->disk('public')
                            ->directory('ticket-responses')
                            ->maxSize(5120)
                            ->imagePreviewHeight('150')
                            ->nullable(),

                        // Toggle::make('is_internal')
                        //     ->label('Internal Note')
                        //     ->default(false),
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

                TextColumn::make('image')
                    ->openUrlInNewTab()
                    ->badge()
                    ->color('info')
                    //->directory('ticket-responses')
                    ->url(fn($record) => asset('/' . $record->image))
                    ->formatStateUsing(fn () => 'Lihat Gambar'),

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
                        $ticket = $this->ownerRecord;
                        $ticket->update(['allow_user_reply' => true]);

                        // Send notification to ticket owner
                        $latestResponse = $ticket->responses()->latest()->first();
                        if ($latestResponse && $ticket->submitter) {
                            $ticket->submitter->notify(
                                new TicketRepliedNotification($ticket, $latestResponse)
                            );
                        }
                    }),
            ]);
    }
}