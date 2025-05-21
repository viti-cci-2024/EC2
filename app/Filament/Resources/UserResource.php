<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Administration';
    
    protected static ?string $navigationLabel = 'Utilisateurs';
    
    protected static ?string $modelLabel = 'Utilisateur';
    
    protected static ?string $pluralModelLabel = 'Utilisateurs';
    
    protected static ?int $navigationSort = 1;
    
    // Seuls les administrateurs peuvent accéder à cette ressource
    public static function canAccess(): bool
    {
        // Vérification directe du rôle plutôt que d'utiliser la méthode isAdmin()
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations utilisateur')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->label('Rôle')
                            ->options([
                                'admin' => 'Administrateur',
                                'employee' => 'Employé',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrateStateUsing(fn (?string $state) => 
                                filled($state) ? bcrypt($state) : null
                            )
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Réintroduction progressive des fonctionnalités sans le tri
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'employee' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrateur',
                        'employee' => 'Employé',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Voir'),
                Tables\Actions\EditAction::make()
                    ->label('Éditer'),
                // Remplacer le bouton Supprimer par un bouton qui redirige vers la page Éditer
                Tables\Actions\Action::make('delete_redirect')
                    ->label('Supprimer')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->url(fn (User $record): string => UserResource::getUrl('edit', ['record' => $record]))
                    // Désactiver la suppression pour le dernier administrateur
                    ->visible(function (?User $record): bool {
                        // Ne pas afficher le bouton pour le dernier administrateur
                        return $record ? !($record->isAdmin() && User::countAdmins() <= 1) : true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer la sélection')
                        ->requiresConfirmation()
                        ->modalHeading('Supprimer les utilisateurs sélectionnés')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer ces utilisateurs ? Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer')
                        ->modalCancelActionLabel('Annuler')
                        // Empêcher la suppression des administrateurs s'il n'y a qu'un seul administrateur
                        ->deselectRecordsAfterCompletion()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $adminCount = User::countAdmins();
                            $selectedAdmins = $records->filter(fn (User $record) => $record->isAdmin())->count();
                            
                            // Vérifier si la suppression laisserait au moins un administrateur
                            if ($adminCount - $selectedAdmins >= 1) {
                                // Supprimer normalement
                                $records->each->delete();
                                
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Utilisateurs supprimés')
                                    ->send();
                            } else {
                                // Afficher une notification d'erreur
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Action impossible')
                                    ->body('Vous ne pouvez pas supprimer tous les administrateurs. Créez un autre administrateur d\'abord.')
                                    ->persistent()
                                    ->send();
                            }
                        }),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
