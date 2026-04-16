<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\HistoryKeuanganPartnerResource;
use App\Models\HistoryKeuanganPartner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WithdrawalRequestStats extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingCount = HistoryKeuanganPartner::query()
            ->where('tipe', 'withdrawal')
            ->where('status', HistoryKeuanganPartner::STATUS_MENUNGGU)
            ->count();

        return [
            Stat::make('Permintaan Withdrawal', (string) $pendingCount)
                ->description('Klik untuk buka menu History Keuangan')
                ->descriptionIcon('heroicon-o-arrow-right')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->url(HistoryKeuanganPartnerResource::getUrl('index')),
        ];
    }
}

