<?php
// database/seeders/FilamentColorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilamentColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'primary', 'label' => 'Primary', 'class_name' => 'primary', 'hex_value' => '#3b82f6'],
            ['name' => 'secondary', 'label' => 'Secondary', 'class_name' => 'secondary', 'hex_value' => '#6b7280'],
            ['name' => 'success', 'label' => 'Success', 'class_name' => 'success', 'hex_value' => '#10b981'],
            ['name' => 'danger', 'label' => 'Danger', 'class_name' => 'danger', 'hex_value' => '#ef4444'],
            ['name' => 'warning', 'label' => 'Warning', 'class_name' => 'warning', 'hex_value' => '#f59e0b'],
            ['name' => 'info', 'label' => 'Info', 'class_name' => 'info', 'hex_value' => '#3b82f6'],
            ['name' => 'gray', 'label' => 'Gray', 'class_name' => 'gray', 'hex_value' => '#6b7280'],
            ['name' => 'slate', 'label' => 'Slate', 'class_name' => 'slate', 'hex_value' => '#64748b'],
            ['name' => 'zinc', 'label' => 'Zinc', 'class_name' => 'zinc', 'hex_value' => '#71717a'],
            ['name' => 'neutral', 'label' => 'Neutral', 'class_name' => 'neutral', 'hex_value' => '#737373'],
            ['name' => 'stone', 'label' => 'Stone', 'class_name' => 'stone', 'hex_value' => '#78716c'],
            ['name' => 'red', 'label' => 'Red', 'class_name' => 'red', 'hex_value' => '#ef4444'],
            ['name' => 'orange', 'label' => 'Orange', 'class_name' => 'orange', 'hex_value' => '#f97316'],
            ['name' => 'amber', 'label' => 'Amber', 'class_name' => 'amber', 'hex_value' => '#f59e0b'],
            ['name' => 'yellow', 'label' => 'Yellow', 'class_name' => 'yellow', 'hex_value' => '#eab308'],
            ['name' => 'lime', 'label' => 'Lime', 'class_name' => 'lime', 'hex_value' => '#84cc16'],
            ['name' => 'green', 'label' => 'Green', 'class_name' => 'green', 'hex_value' => '#22c55e'],
            ['name' => 'emerald', 'label' => 'Emerald', 'class_name' => 'emerald', 'hex_value' => '#10b981'],
            ['name' => 'teal', 'label' => 'Teal', 'class_name' => 'teal', 'hex_value' => '#14b8a6'],
            ['name' => 'cyan', 'label' => 'Cyan', 'class_name' => 'cyan', 'hex_value' => '#06b6d4'],
            ['name' => 'sky', 'label' => 'Sky', 'class_name' => 'sky', 'hex_value' => '#0ea5e9'],
            ['name' => 'blue', 'label' => 'Blue', 'class_name' => 'blue', 'hex_value' => '#3b82f6'],
            ['name' => 'indigo', 'label' => 'Indigo', 'class_name' => 'indigo', 'hex_value' => '#6366f1'],
            ['name' => 'violet', 'label' => 'Violet', 'class_name' => 'violet', 'hex_value' => '#8b5cf6'],
            ['name' => 'purple', 'label' => 'Purple', 'class_name' => 'purple', 'hex_value' => '#a855f7'],
            ['name' => 'fuchsia', 'label' => 'Fuchsia', 'class_name' => 'fuchsia', 'hex_value' => '#d946ef'],
            ['name' => 'pink', 'label' => 'Pink', 'class_name' => 'pink', 'hex_value' => '#ec4899'],
            ['name' => 'rose', 'label' => 'Rose', 'class_name' => 'rose', 'hex_value' => '#f43f5e'],
        ];

        foreach ($colors as $index => $color) {
            DB::table('filament_colors')->insert([
                'name' => $color['name'],
                'label' => $color['label'],
                'class_name' => $color['class_name'],
                'hex_value' => $color['hex_value'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
