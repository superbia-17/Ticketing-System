<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsByStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status';

    protected ?string $description = 'Persentase tiket berdasarkan status';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '260px';

    protected string $color = 'warning';

    protected function getData(): array
    {
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        $counts = collect($statuses)->map(function ($status) {
            return Ticket::where('status', $status)->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // open - blue
                        'rgba(245, 158, 11, 0.8)',   // in_progress - amber
                        'rgba(34, 197, 94, 0.8)',    // resolved - green
                        'rgba(107, 114, 128, 0.8)',  // closed - gray
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(34, 197, 94)',
                        'rgb(107, 114, 128)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Open', 'In Progress', 'Resolved', 'Closed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
