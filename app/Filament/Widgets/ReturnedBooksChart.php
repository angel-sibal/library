<?php

namespace App\Filament\Widgets;

use App\Models\BorrowedBook;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\LineChartWidget;

class ReturnedBooksChart extends LineChartWidget
{
    protected static ?string $heading = 'Returned books per day in the month of April 2023';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        $data = Trend::query(BorrowedBook::onlyTrashed())
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
            column: 'deleted_at',
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
