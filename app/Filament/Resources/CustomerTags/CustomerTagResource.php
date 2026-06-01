<?php

namespace App\Filament\Resources\CustomerTags;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\CustomerTags\Pages\CreateCustomerTag;
use App\Filament\Resources\CustomerTags\Pages\EditCustomerTag;
use App\Filament\Resources\CustomerTags\Pages\ListCustomerTags;
use App\Filament\Resources\CustomerTags\Schemas\CustomerTagForm;
use App\Filament\Resources\CustomerTags\Tables\CustomerTagsTable;
use App\Models\CustomerTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomerTagResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = CustomerTag::class;

    protected static ?string $viewPermission = 'customer_view';

    protected static ?string $editPermission = 'customer_edit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Musteri Yonetimi';

    protected static ?string $navigationLabel = 'Musteri Etiketleri';

    protected static ?string $modelLabel = 'Musteri Etiketi';

    protected static ?string $pluralModelLabel = 'Musteri Etiketleri';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CustomerTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerTagsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerTags::route('/'),
            'create' => CreateCustomerTag::route('/create'),
            'edit' => EditCustomerTag::route('/{record}/edit'),
        ];
    }
}
