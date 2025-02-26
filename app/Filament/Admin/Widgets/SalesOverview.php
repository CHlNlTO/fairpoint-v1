<?php
// app/Filament/Widgets/SalesOverview.php

namespace App\Filament\Widgets;

use App\Models\SalesTransaction;
use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesOverview extends ChartWidget
{
    protected static ?string $heading = 'Monthly Sales Overview';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        // Get all months of the current year
        $months = [];
        $datasets = [];

        // Get all revenue accounts
        $revenueAccounts = ChartOfAccount::where('type', 'Revenue')->get();

        // Initialize monthly total data
        $monthlyTotals = collect();

        // For each month, get the total sales
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::createFromDate($year, $month, 1);
            $months[] = $date->format('M');

            // Calculate total sales for the month
            $monthlyTotals[$month] = SalesTransaction::whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
        }

        // Add total sales dataset
        $datasets[] = [
            'label' => 'Total Sales',
            'data' => $monthlyTotals->values()->toArray(),
            'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
            'borderColor' => 'rgb(59, 130, 246)',
        ];

        // For each revenue account, get monthly data
        foreach ($revenueAccounts as $index => $account) {
            $monthlyData = collect();

            for ($month = 1; $month <= 12; $month++) {
                // Calculate sales for this account for the month
                $monthlyData[$month] = SalesTransaction::whereMonth('transaction_date', $month)
                    ->whereYear('transaction_date', $year)
                    ->where('account_id', $account->id)
                    ->sum('amount');
            }

            // Generate a unique color for this account
            $hue = ($index * 137) % 360; // Use golden ratio to distribute colors

            $datasets[] = [
                'label' => $account->name,
                'data' => $monthlyData->values()->toArray(),
                'backgroundColor' => "hsla({$hue}, 70%, 60%, 0.5)",
                'borderColor' => "hsl({$hue}, 70%, 50%)",
            ];
        }

        return [
            'labels' => $months,
            'datasets' => $datasets,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
