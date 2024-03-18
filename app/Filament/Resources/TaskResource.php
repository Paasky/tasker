<?php

namespace App\Filament\Resources;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->maxLength(16384),

                Forms\Components\Select::make('priority')
                    ->default(TaskPriority::NONE)
                    ->options(TaskPriority::options())
                    ->required(),

                Forms\Components\Select::make('status')
                    ->default(TaskStatus::BACKLOG)
                    ->options(TaskStatus::options())
                    ->required(),

                Forms\Components\DatePicker::make('due')
                    ->minDate(now()),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (TaskPriority $state) => match ($state) {
                        TaskPriority::NONE, TaskPriority::LOW => 'gray',
                        TaskPriority::MEDIUM => 'info',
                        TaskPriority::HIGH => 'warning',
                        TaskPriority::URGENT => 'danger',
                    })
                    ->formatStateUsing(fn (TaskPriority $state): string => $state->title())
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (TaskStatus $state) => match ($state) {
                        TaskStatus::BACKLOG, TaskStatus::TODO => 'gray',
                        TaskStatus::IN_QA, TaskStatus::IN_PROGRESS => 'info',
                        TaskStatus::READY_FOR_QA, TaskStatus::READY_TO_DEPLOY => 'warning',
                        TaskStatus::DEPLOYED, TaskStatus::DONE => 'success',
                        TaskStatus::CANCELLED => 'danger',
                    })
                    ->formatStateUsing(fn (TaskStatus $state): string => $state->title())
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->options(TaskPriority::options()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
