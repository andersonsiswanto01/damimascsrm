<?php

namespace App\Filament\Resources\MySalesPipelines\Pages;

use App\Filament\Resources\MySalesPipelines\MySalesPipelines\MySalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMySalesPipeline extends CreateRecord
{
    protected static string $resource = MySalesPipelineResource::class;
}
