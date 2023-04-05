<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\BorrowedBook;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\BarChartWidget;

class BorrowedBooksChart extends BarChartWidget
{
    protected static ?string $heading = 'Borrowed books per day in the month of April 2023';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $data = Trend::model(BorrowedBook::class)
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Borrowed books',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#fb9101',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
