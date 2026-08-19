@if ($preview)
    <div class="fi-su-import-box">
        <p class="fi-su-import-heading">
            {{ __('filament-short-url::resources/import.preview_heading', ['total' => $preview['totalRows']]) }}
        </p>

        @foreach ($preview['warnings'] as $warning)
            <p class="fi-su-import-warning">{{ $warning }}</p>
        @endforeach

        @if ($preview['sampleRows'] !== [])
            <div class="fi-su-import-table-wrap">
                <table class="fi-su-import-table">
                    <thead>
                        <tr>
                            @foreach ($preview['columns'] as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview['sampleRows'] as $row)
                            <tr>
                                @foreach ($preview['columns'] as $column)
                                    <td>{{ data_get($row, $column) }}</td>
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
    <div class="fi-su-import-box">
        <p class="fi-su-import-summary">
            {{ __('filament-short-url::resources/import.report_summary', ['imported' => $report['imported'], 'skipped' => $report['skipped'], 'failed' => $report['failed']]) }}
        </p>

        @foreach ($report['errors'] as $error)
            <p class="fi-su-import-error">{{ $error }}</p>
        @endforeach
    </div>
@endif
