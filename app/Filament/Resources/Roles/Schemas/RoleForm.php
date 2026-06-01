<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rol Bilgileri')
                    ->schema([
                        TextInput::make('name')
                            ->label('Rol Adi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Aciklama')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_system')
                            ->label('Sistem Rolu')
                            ->default(false)
                            ->inline(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Yetkiler')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('Yetkiler')
                            ->relationship('permissions', 'name')
                            ->columns(2)
                            ->bulkToggleable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
