@if ($preview)
    <div class="mt-4 space-y-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-950 dark:text-white">
            {{ __('filament-short-url::resources/import.preview_heading', ['total' => $preview['totalRows']]) }}
        </p>

        @foreach ($preview['warnings'] as $warning)
            <p class="text-sm text-warning-600 dark:text-warning-400">{{ $warning }}</p>
        @endforeach

        @if ($preview['sampleRows'] !== [])
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr>
                            @foreach ($preview['columns'] as $column)
                                <th class="px-2 py-1 font-medium text-gray-500 dark:text-gray-400">{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview['sampleRows'] as $row)
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                @foreach ($preview['columns'] as $column)
                                    <td class="truncate px-2 py-1 text-gray-700 dark:text-gray-200">{{ data_get($row, $column) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif

@if ($report)
    <div class="mt-4 space-y-2 rounded-lg border border-gray-200 p-4 dark:border-gray-700">
        <p class="text-sm text-gray-950 dark:text-white">
            {{ __('filament-short-url::resources/import.report_summary', ['imported' => $report['imported'], 'skipped' => $report['skipped'], 'failed' => $report['failed']]) }}
        </p>

        @foreach ($report['errors'] as $error)
            <p class="text-sm text-danger-600 dark:text-danger-400">{{ $error }}</p>
        @endforeach
    </div>
@endif
