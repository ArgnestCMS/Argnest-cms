<?php

namespace App\Filament\Resources\CustomerAddresses;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\CustomerAddresses\Pages\CreateCustomerAddress;
use App\Filament\Resources\CustomerAddresses\Pages\EditCustomerAddress;
use App\Filament\Resources\CustomerAddresses\Pages\ListCustomerAddresses;
use App\Filament\Resources\CustomerAddresses\Schemas\CustomerAddressForm;
use App\Filament\Resources\CustomerAddresses\Tables\CustomerAddressesTable;
use App\Models\CustomerAddress;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomerAddressResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = CustomerAddress::class;

    protected static ?string $viewPermission = 'customer_view';

    protected static ?string $editPermission = 'customer_edit';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Musteri Yonetimi';

    protected static ?string $navigationLabel = 'Musteri Adresleri';

    protected static ?string $modelLabel = 'Musteri Adresi';

    protected static ?string $pluralModelLabel = 'Musteri Adresleri';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CustomerAddressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerAddressesTable::configure($table);
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
            'index' => ListCustomerAddresses::route('/'),
            'create' => CreateCustomerAddress::route('/create'),
            'edit' => EditCustomerAddress::route('/{record}/edit'),
        ];
    }
}
