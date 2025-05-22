<?php

namespace App\Filament\Resources\BungalowResource\Pages;

use App\Filament\Resources\BungalowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBungalows extends ListRecords
{
    protected static string $resource = BungalowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
