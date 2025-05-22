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
    
    protected static ?string $modelLabel = 'Réservation';
    
    protected static ?string $pluralModelLabel = 'Réservations';
    
    protected static ?int $navigationSort = 2;
    
    // Constantes pour éviter la duplication
    private const BUNGALOW_MER = 'Bungalow mer';
    private const BUNGALOW_JARDIN = 'Bungalow jardin';
    private const DATE_FORMAT = 'd/m/Y';

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
                            // S'assurer que le nom est correctement formaté (avec guillemets)
                            ->beforeStateDehydrated(function ($state, callable $set) {
                                if (is_string($state)) {
                                    $set('last_name', $state);
                                }
                            })
                            ->columnSpan(2),
                
                        Forms\Components\Select::make('bungalow_id')
                            ->label('Type de Bungalow')
                            ->options(function () {
                                // Récupérer tous les bungalows groupés par type
                                $bungalows = \App\Models\Bungalow::all()
                                    ->groupBy('type')
                                    ->map(function ($group) {
                                        // Pour chaque type, prendre le premier bungalow disponible
                                        return $group->first();
                                    });
                                
                                // Créer un tableau d'options avec les IDs comme clés
                                $options = [];
                                foreach ($bungalows as $bungalow) {
                                    $label = $bungalow->type === 'mer' ? self::BUNGALOW_MER : self::BUNGALOW_JARDIN;
                                    $options[$bungalow->id] = $label;
                                }
                                
                                return $options;
                            })
                            ->required()
                            ->reactive() // Rendre réactif pour mettre à jour le nombre max de personnes
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                // Récupérer le type du bungalow sélectionné
                                $bungalow = \App\Models\Bungalow::find($state);
                                if (!$bungalow) {
                                    return;
                                }
                                
                                $bungalowType = $bungalow->type;
                                
                                // Si le nombre de personnes dépasse la limite du nouveau type de bungalow, ajuster
                                $currentPersonCount = $get('person_count');
                                $maxPersons = $bungalowType === 'mer' ? 2 : 4;
                                
                                if ($currentPersonCount > $maxPersons) {
                                    $set('person_count', $maxPersons);
                                }
                            })
                            ->columnSpan(2),
                
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required()
                            ->minDate(now()->startOfDay())
                            ->displayFormat(self::DATE_FORMAT)
                            ->closeOnDateSelection(),
                            
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required()
                            ->minDate(fn (Forms\Get $get) => 
                                $get('start_date') ? \Carbon\Carbon::parse($get('start_date'))->addDay() : now()->addDay())
                            ->displayFormat(self::DATE_FORMAT)
                            ->closeOnDateSelection()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                if ($get('start_date') && $state && \Carbon\Carbon::parse($state)->lte(\Carbon\Carbon::parse($get('start_date')))) {
                                    $set('end_date', \Carbon\Carbon::parse($get('start_date'))->addDay()->format('Y-m-d'));
                                }
                            }),
                
                        Forms\Components\TextInput::make('person_count')
                            ->label('Nombre de personnes')
                            ->required()
                            ->type('number')
                            ->rules(['integer', 'min:1'])
                            ->minValue(1)
                            // Limite dynamique selon le type de bungalow
                            ->maxValue(function (Forms\Get $get) {
                                $bungalowId = $get('bungalow_id');
                                if (!$bungalowId) {
                                    return 4; // Valeur par défaut
                                }
                                
                                $bungalow = \App\Models\Bungalow::find($bungalowId);
                                if (!$bungalow) {
                                    return 4; // Valeur par défaut
                                }
                                
                                return $bungalow->type === 'mer' ? 2 : 4;
                            })
                            // Mise à jour automatique si la valeur dépasse la limite
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                $bungalowId = $get('bungalow_id');
                                if (!$bungalowId) {
                                    return;
                                }
                                
                                $bungalow = \App\Models\Bungalow::find($bungalowId);
                                if (!$bungalow) {
                                    return;
                                }
                                
                                $maxPersons = $bungalow->type === 'mer' ? 2 : 4;
                                if ($state > $maxPersons) {
                                    $set('person_count', $maxPersons);
                                }
                            }),
                    
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
                    ->searchable(false)
                    ->copyable()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable(false)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('bungalow_type')
                    ->label('Type de bungalow')
                    ->formatStateUsing(fn (?string $state): string => 
                        $state === 'mer' ? self::BUNGALOW_MER : self::BUNGALOW_JARDIN)
                    ->badge()
                    ->color(fn (?string $state): string => 
                        $state === 'mer' ? 'info' : 'success')
                    ->searchable(false),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Début')
                    ->date(self::DATE_FORMAT),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fin')
                    ->date(self::DATE_FORMAT),
                Tables\Columns\TextColumn::make('person_count')
                    ->label('Personnes')
                    // Utiliser un formatage personnalisé au lieu de ->numeric()
                    ->formatStateUsing(fn (int $state): string => (string) $state),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime(self::DATE_FORMAT . ' H:i')
                    ->visible(false), // Caché plutôt que toggleable
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([]) // Retirer tous les filtres
            ->filtersFormColumns(1)
            ->filtersTriggerAction(null) // Supprimer le bouton de filtres
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Voir'),
                    Tables\Actions\EditAction::make()
                        ->label('Éditer'),
                    // Remplacer l'action de suppression directe par une redirection vers la page d'édition
                    Tables\Actions\Action::make('delete_redirect')
                        ->label('Supprimer')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->url(fn (Reservation $record): string => static::getUrl('edit', ['record' => $record])),
                ])
            ])
            ->bulkActions([])
            ->toggleColumnsTriggerAction(null); // Retirer le bouton de basculement des colonnes
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
        // La création de réservation est maintenant gérée directement par le modèle
        // avec le champ bungalow_id, donc nous n'avons plus besoin de code supplémentaire ici
        
        // L'observateur ReservationObserver s'occupe de générer le numéro de réservation
        // et de formater correctement les données
    }
    
    // Hook pour le processus de mise à jour d'une réservation
    public static function afterSave($livewire): void
    {
        // La mise à jour de réservation est maintenant gérée directement par le modèle
        // avec le champ bungalow_id, donc nous n'avons plus besoin de code supplémentaire ici
    }
}
