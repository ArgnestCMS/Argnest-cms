<?php

namespace App\Filament\Resources\HeroButtons;

use App\Filament\Resources\HeroButtons\Pages\CreateHeroButton;
use App\Filament\Resources\HeroButtons\Pages\EditHeroButton;
use App\Filament\Resources\HeroButtons\Pages\ListHeroButtons;
use App\Filament\Resources\HeroButtons\Schemas\HeroButtonForm;
use App\Filament\Resources\HeroButtons\Tables\HeroButtonsTable;
use App\Models\HeroButton;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeroButtonResource extends Resource
{
    protected static ?string $model = HeroButton::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCursorArrowRays;

    protected static string|UnitEnum|null $navigationGroup = 'Site Yönetimi';

    protected static ?string $navigationLabel = 'Hero Butonları';

    protected static ?string $modelLabel = 'Hero Butonu';

    protected static ?string $pluralModelLabel = 'Hero Butonları';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return HeroButtonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroButtonsTable::configure($table);
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
            'index' => ListHeroButtons::route('/'),
            'create' => CreateHeroButton::route('/create'),
            'edit' => EditHeroButton::route('/{record}/edit'),
        ];
    }
}
