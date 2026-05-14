<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsByPriorityChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Prioritas';

    protected ?string $description = 'Jumlah tiket berdasarkan tingkat prioritas';

    protected static ?int $sort = 4;

    protected ?string $maxHeight = '260px';

    protected string $color = 'danger';

    protected function getData(): array
    {
        $priorities = ['low', 'medium', 'high'];

        $counts = collect($priorities)->map(function ($priority) {
            return Ticket::where('priority', $priority)->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',   // low - blue
                        'rgba(245, 158, 11, 0.8)',   // medium - amber
                        'rgba(239, 68, 68, 0.8)',    // high - red
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Rendah', 'Sedang', 'Tinggi'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
