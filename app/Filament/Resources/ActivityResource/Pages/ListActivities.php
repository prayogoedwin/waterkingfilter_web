<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cleanup_old')
                ->label('Cleanup Old Logs (90+ days)')
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Cleanup Old Activity Logs')
                ->modalDescription('This will permanently delete all activity logs older than 90 days. This action cannot be undone.')
                ->modalSubmitActionLabel('Delete Old Logs')
                ->action('cleanupOldLogs'),
                
            Action::make('cleanup_all')
                ->label('Delete All Logs')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Delete All Activity Logs')
                ->modalDescription('⚠️ WARNING: This will permanently delete ALL activity logs. This action cannot be undone!')
                ->modalSubmitActionLabel('Delete All Logs')
                ->action('cleanupAllLogs'),
                
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Activities'),
            
            'today' => Tab::make('Today')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereDate('created_at', today())
                )
                ->badge(fn () => 
                    $this->getModel()::whereDate('created_at', today())->count()
                ),
            
            'this_week' => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])
                )
                ->badge(fn () => 
                    $this->getModel()::whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])->count()
                ),
            
            'created' => Tab::make('Created')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('event', 'created')
                )
                ->badge(fn () => 
                    $this->getModel()::where('event', 'created')->count()
                ),
            
            'updated' => Tab::make('Updated')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('event', 'updated')
                )
                ->badge(fn () => 
                    $this->getModel()::where('event', 'updated')->count()
                ),
            
            'deleted' => Tab::make('Deleted')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('event', 'deleted')
                )
                ->badge(fn () => 
                    $this->getModel()::where('event', 'deleted')->count()
                ),
        ];
    }

       public function cleanupOldLogs()
    {
        $modelClass = $this->getModel();
        $count = $modelClass::where('created_at', '<', now()->subDays(90))->count();
        
        if ($count > 0) {
            $modelClass::where('created_at', '<', now()->subDays(90))->delete();
            
            Notification::make()
                ->title('Cleanup Successful')
                ->body("Deleted {$count} old activity logs.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('No Action Needed')
                ->body('No old logs to cleanup.')
                ->info()
                ->send();
        }
    }
    
    public function cleanupAllLogs()
    {
        $modelClass = $this->getModel();
        $count = $modelClass::count();
        
        if ($count > 0) {
            $modelClass::query()->delete();
            
            Notification::make()
                ->title('Cleanup Successful')
                ->body("Deleted all {$count} activity logs.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('No Action Needed')
                ->body('No activity logs to delete.')
                ->info()
                ->send();
        }
    }
}