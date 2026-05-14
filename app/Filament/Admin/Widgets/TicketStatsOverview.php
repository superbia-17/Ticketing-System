<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TicketStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'open')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        $resolvedTickets = Ticket::where('status', 'resolved')->count();
        $closedTickets = Ticket::where('status', 'closed')->count();

        // Calculate resolution rate
        $resolvedAndClosed = $resolvedTickets + $closedTickets;
        $resolutionRate = $totalTickets > 0
            ? round(($resolvedAndClosed / $totalTickets) * 100, 1)
            : 0;

        // Sparkline data: daily ticket creation for the last 7 days
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            return Ticket::whereDate('created_at', Carbon::today()->subDays($daysAgo))->count();
        })->toArray();

        // Sparkline: daily resolved tickets for the last 7 days
        $last7DaysResolved = collect(range(6, 0))->map(function ($daysAgo) {
            return Ticket::whereIn('status', ['resolved', 'closed'])
                ->whereDate('updated_at', Carbon::today()->subDays($daysAgo))
                ->count();
        })->toArray();

        // Today's new tickets vs yesterday
        $todayCount = Ticket::whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = Ticket::whereDate('created_at', Carbon::yesterday())->count();
        $newTodayDiff = $todayCount - $yesterdayCount;
        $newTodayDesc = $newTodayDiff >= 0
            ? "{$todayCount} hari ini (+{$newTodayDiff})"
            : "{$todayCount} hari ini ({$newTodayDiff})";

        return [
            Stat::make('Total Tiket', $totalTickets)
                ->description($newTodayDesc)
                ->descriptionIcon($newTodayDiff >= 0 ? Heroicon::OutlinedArrowTrendingUp : Heroicon::OutlinedArrowTrendingDown)
                ->descriptionColor($newTodayDiff >= 0 ? 'warning' : 'success')
                ->color('warning')
                ->chart($last7Days),

            Stat::make('Tiket Open', $openTickets)
                ->description('Menunggu ditangani')
                ->descriptionIcon(Heroicon::OutlinedExclamationCircle)
                ->descriptionColor('danger')
                ->color('danger')
                ->chart($last7Days),

            Stat::make('Sedang Diproses', $inProgressTickets)
                ->description('Dalam pengerjaan')
                ->descriptionIcon(Heroicon::OutlinedCog6Tooth)
                ->descriptionColor('info')
                ->color('info'),

            Stat::make('Terselesaikan', $resolvedAndClosed)
                ->description("Rate: {$resolutionRate}%")
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->descriptionColor('success')
                ->color('success')
                ->chart($last7DaysResolved),
        ];
    }
}
