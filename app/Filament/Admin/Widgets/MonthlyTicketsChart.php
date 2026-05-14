<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyTicketsChart extends ChartWidget
{
    protected ?string $heading = 'Tiket Bulanan';

    protected ?string $description = 'Jumlah tiket masuk per bulan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '280px';

    protected string $color = 'warning';

    protected function getFilters(): ?array
    {
        $currentYear = now()->year;

        return [
            $currentYear => $currentYear,
            $currentYear - 1 => $currentYear - 1,
            $currentYear - 2 => $currentYear - 2,
        ];
    }

    protected function getData(): array
    {
        $year = $this->filter ?? now()->year;

        $months = collect(range(1, 12));

        $created = $months->map(function ($month) use ($year) {
            return Ticket::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
        })->toArray();

        $resolved = $months->map(function ($month) use ($year) {
            return Ticket::whereIn('status', ['resolved', 'closed'])
                ->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month)
                ->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tiket Masuk',
                    'data' => $created,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => true,
                ],
                [
                    'label' => 'Tiket Selesai',
                    'data' => $resolved,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => true,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
