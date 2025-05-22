<?php

namespace App\Filament\Resources\BungalowResource\Pages;

use App\Filament\Resources\BungalowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBungalow extends EditRecord
{
    protected static string $resource = BungalowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
