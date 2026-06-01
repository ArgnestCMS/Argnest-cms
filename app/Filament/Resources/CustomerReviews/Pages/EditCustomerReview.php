<?php

namespace App\Filament\Resources\CustomerReviews\Pages;

use App\Filament\Resources\CustomerReviews\CustomerReviewResource;
use App\Models\CustomerNotification;
use App\Models\CustomerReview;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomerReview extends EditRecord
{
    protected static string $resource = CustomerReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Onayla')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status !== CustomerReview::STATUS_APPROVED)
                ->action(function (): void {
                    $this->record->forceFill([
                        'status' => CustomerReview::STATUS_APPROVED,
                        'approved_at' => now(),
                    ])->save();

                    CustomerNotification::query()->create([
                        'user_id' => $this->record->user_id,
                        'title' => 'Yorumunuz onaylandi',
                        'message' => 'Musteri yorumunuz onaylandi ve yayina alindi.',
                        'type' => 'review',
                        'link' => route('frontend.customer.reviews.index'),
                    ]);
                }),
            Action::make('reject')
                ->label('Reddet')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status !== CustomerReview::STATUS_REJECTED)
                ->action(function (): void {
                    $this->record->forceFill([
                        'status' => CustomerReview::STATUS_REJECTED,
                        'approved_at' => null,
                    ])->save();
                }),
            DeleteAction::make()
                ->label('Sil'),
        ];
    }
}
