<?php

namespace App\Filament\Resources\CustomerFiles;

use App\Filament\Resources\Concerns\ChecksResourcePermissions;
use App\Filament\Resources\CustomerFiles\Pages\CreateCustomerFile;
use App\Filament\Resources\CustomerFiles\Pages\EditCustomerFile;
use App\Filament\Resources\CustomerFiles\Pages\ListCustomerFiles;
use App\Filament\Resources\CustomerFiles\Schemas\CustomerFileForm;
use App\Filament\Resources\CustomerFiles\Tables\CustomerFilesTable;
use App\Models\CustomerFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomerFileResource extends Resource
{
    use ChecksResourcePermissions;

    protected static ?string $model = CustomerFile::class;

    protected static ?string $viewPermission = 'file_view';

    protected static ?string $editPermission = 'file_upload';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static string|UnitEnum|null $navigationGroup = 'Musteri Yonetimi';

    protected static ?string $navigationLabel = 'Musteri Dosyalari';

    protected static ?string $modelLabel = 'Musteri Dosyasi';

    protected static ?string $pluralModelLabel = 'Musteri Dosyalari';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CustomerFileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerFilesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerFiles::route('/'),
            'create' => CreateCustomerFile::route('/create'),
            'edit' => EditCustomerFile::route('/{record}/edit'),
        ];
    }
}
