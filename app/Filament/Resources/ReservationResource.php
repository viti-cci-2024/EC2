<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Bungalow;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';    
    protected static ?string $navigationGroup = 'Gestion';
    
    protected static ?string $navigationLabel = 'Réservations';
    
    protected static ?int $navigationSort = 2;
    
    // Constantes pour éviter la duplication
    private const BUNGALOW_MER = 'Bungalow mer';
    private const BUNGALOW_JARDIN = 'Bungalow jardin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la réservation')
                    ->schema([
                        Forms\Components\TextInput::make('last_name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                
                        Forms\Components\Select::make('bungalow_type')
                            ->label('Type de Bungalow')
                            ->options([
                                'mer' => self::BUNGALOW_MER,
                                'jardin' => self::BUNGALOW_JARDIN,
                            ])
                            ->afterStateHydrated(function (Forms\Components\Select $component, $record) {
                                if ($record) {
                                    $bungalow = $record->bungalows()->first();
                                    if ($bungalow) {
                                        $component->state($bungalow->type);
                                    }
                                }
                            })
                            ->required()
                            ->columnSpan(2),
                
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required()
                            ->minDate(now())
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection(),
                            
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required()
                            ->minDate(fn (Forms\Get $get) => 
                                $get('start_date') ? \Carbon\Carbon::parse($get('start_date'))->addDay() : now()->addDay())
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                if ($get('start_date') && $state && \Carbon\Carbon::parse($state)->lte(\Carbon\Carbon::parse($get('start_date')))) {
                                    $set('end_date', \Carbon\Carbon::parse($get('start_date'))->addDay()->format('Y-m-d'));
                                }
                            }),
                
                        Forms\Components\TextInput::make('person_count')
                            ->label('Nombre de personnes')
                            ->required()
                            ->type('number') // Utiliser le type HTML5 au lieu de ->numeric()
                            ->rules(['integer', 'min:1', 'max:8']) // Validation côté serveur
                            ->minValue(1)
                            ->maxValue(8),
                    
                        Forms\Components\TextInput::make('numero')
                            ->label('Numéro de réservation')
                            ->required()
                            ->maxLength(255)
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->hidden(fn ($livewire) => $livewire instanceof Pages\CreateReservation)
                            ->disabled()
                            ->hint('Généré automatiquement')
                            ->placeholder('CH25050001')
                            ->suffixIcon('heroicon-m-information-circle')
                            ->columnSpan(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('N° Réservation')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('bungalows.type')
                    ->label('Type de bungalow')
                    ->formatStateUsing(fn (?string $state): string => 
                        $state === 'mer' ? 'Bungalow mer' : 'Bungalow jardin')
                    ->badge()
                    ->color(fn (?string $state): string => 
                        $state === 'mer' ? 'info' : 'success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Début')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('person_count')
                    ->label('Personnes')
                    // Utiliser un formatage personnalisé au lieu de ->numeric()
                    ->formatStateUsing(fn (int $state): string => (string) $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('bungalow_type')
                    ->label('Type de bungalow')
                    ->relationship('bungalows', 'type')
                    ->options([
                        'mer' => self::BUNGALOW_MER,
                        'jardin' => self::BUNGALOW_JARDIN,
                    ]),
                Tables\Filters\Filter::make('current_reservations')
                    ->label('Réservations en cours')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('start_date', '<=', today())
                            ->where('end_date', '>=', today())),
                Tables\Filters\Filter::make('future_reservations')
                    ->label('Réservations futures')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('start_date', '>', today())),
                Tables\Filters\Filter::make('past_reservations')
                    ->label('Réservations passées')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('end_date', '<', today())),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Voir'),
                    Tables\Actions\EditAction::make()
                        ->label('Éditer'),
                    Tables\Actions\Action::make('change_bungalow')
                        ->label('Changer de bungalow')
                        ->icon('heroicon-o-arrows-right-left')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('new_bungalow_id')
                                ->label('Nouveau bungalow')
                                ->required()
                                ->relationship(
                                    name: 'bungalows',
                                    titleAttribute: 'type',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('disponible', true)
                                )
                                ->preload(),
                        ])
                        ->action(function (Reservation $record, array $data): void {
                            // Détacher tous les bungalows existants
                            $record->bungalows()->detach();
                            // Attacher le nouveau bungalow
                            $record->bungalows()->attach($data['new_bungalow_id'], ['nb_personnes' => $record->person_count]);
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->label('Supprimer')
                        ->requiresConfirmation()
                        ->modalHeading('Supprimer la réservation')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer')
                        ->modalCancelActionLabel('Annuler'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Supprimer la sélection')
                        ->requiresConfirmation()
                        ->modalHeading('Supprimer les réservations sélectionnées')
                        ->modalDescription('Êtes-vous sûr de vouloir supprimer ces réservations ? Cette action est irréversible.')
                        ->modalSubmitActionLabel('Oui, supprimer')
                        ->modalCancelActionLabel('Annuler'),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    // Hook pour le processus de création d'une réservation
    public static function afterCreate($livewire, $record): void
    {
        if (!$record) {
            return; // Sécurité
        }
        
        // Générer un nouveau numéro de réservation
        $lastReservation = \App\Models\Reservation::orderBy('id', 'desc')
            ->where('numero', 'like', 'CH%')
            ->first();
        
        $newNumber = 1;
        $year = date('y');
        $month = date('m');
        
        if ($lastReservation && preg_match('/^CH\d{4}(\d+)$/', $lastReservation->numero, $matches)) {
            $newNumber = intval($matches[1]) + 1;
        }
        
        // Formater le numéro avec un padding de zéros (4 chiffres)
        $numeroFormatted = sprintf('CH%s%s%04d', $year, $month, $newNumber);
        
        // Mettre à jour le numéro de la réservation
        $record->update([
            'numero' => $numeroFormatted
        ]);
        
        // Récupérer le type de bungalow sélectionné
        $bungalowType = $livewire->data['bungalow_type'] ?? null;
        
        if ($bungalowType) {
            // Trouver un bungalow du type sélectionné
            $bungalow = \App\Models\Bungalow::where('type', $bungalowType)
                ->where('disponible', true)
                ->first();
            
            if ($bungalow) {
                // Attacher le bungalow à la réservation
                $record->bungalows()->attach($bungalow->id, [
                    'nb_personnes' => $record->person_count ?? 1
                ]);
            }
        }
    }
    
    // Hook pour le processus de mise à jour d'une réservation
    public static function afterSave($livewire): void
    {
        if (!$livewire) {
            return; // Sécurité
        }
        
        $record = $livewire->getRecord();
        if (!$record) {
            return;
        }
        
        // Récupérer le nouveau type de bungalow sélectionné
        $bungalowType = $livewire->data['bungalow_type'] ?? null;
        
        if ($bungalowType) {
            // Trouver un bungalow du type sélectionné
            $bungalow = \App\Models\Bungalow::where('type', $bungalowType)
                ->where('disponible', true)
                ->first();
            
            if ($bungalow) {
                // Détacher tous les bungalows actuels
                $record->bungalows()->detach();
                
                // Attacher le nouveau bungalow
                $record->bungalows()->attach($bungalow->id, [
                    'nb_personnes' => $record->person_count ?? 1
                ]);
            }
        }
    }
}
