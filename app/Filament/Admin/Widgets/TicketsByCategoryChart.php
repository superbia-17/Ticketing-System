<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class TicketsByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Tiket per Kategori';

    protected ?string $description = 'Jumlah tiket berdasarkan kategori aduan';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '260px';

    protected string $color = 'warning';

    protected function getData(): array
    {
        $categories = Category::withCount('tickets')
            ->orderByDesc('tickets_count')
            ->get();

        $colors = [
            'rgba(245, 158, 11, 0.7)',
            'rgba(59, 130, 246, 0.7)',
            'rgba(239, 68, 68, 0.7)',
            'rgba(34, 197, 94, 0.7)',
            'rgba(168, 85, 247, 0.7)',
            'rgba(236, 72, 153, 0.7)',
            'rgba(20, 184, 166, 0.7)',
            'rgba(249, 115, 22, 0.7)',
        ];

        $borderColors = [
            'rgb(245, 158, 11)',
            'rgb(59, 130, 246)',
            'rgb(239, 68, 68)',
            'rgb(34, 197, 94)',
            'rgb(168, 85, 247)',
            'rgb(236, 72, 153)',
            'rgb(20, 184, 166)',
            'rgb(249, 115, 22)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tiket',
                    'data' => $categories->pluck('tickets_count')->toArray(),
                    'backgroundColor' => array_slice(
                        array_pad($colors, $categories->count(), 'rgba(107, 114, 128, 0.7)'),
                        0,
                        $categories->count()
                    ),
                    'borderColor' => array_slice(
                        array_pad($borderColors, $categories->count(), 'rgb(107, 114, 128)'),
                        0,
                        $categories->count()
                    ),
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
