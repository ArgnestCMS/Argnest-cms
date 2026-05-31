<?php

namespace App\Filament\Resources\CustomerReviews\Pages;

use App\Filament\Resources\CustomerReviews\CustomerReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerReview extends CreateRecord
{
    protected static string $resource = CustomerReviewResource::class;
}
