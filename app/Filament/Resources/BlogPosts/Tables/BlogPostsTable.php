<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label('Görüntülenme')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Öne Çıkarıldı mı')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif mi')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Yayın Tarihi')
                    ->dateTime('d.m.Y H:i', timezone: 'Europe/Istanbul')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiflik Durumu')
                    ->trueLabel('Aktif Yazılar')
                    ->falseLabel('Pasif Yazılar')
                    ->placeholder('Tüm Yazılar'),
                Filter::make('featured')
                    ->label('Öne Çıkan Yazılar')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),
                SelectFilter::make('blog_category_id')
                    ->label('Kategori Filtresi')
                    ->relationship('category', 'name'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make()
                    ->label('Düzenle'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ]);
    }
}
