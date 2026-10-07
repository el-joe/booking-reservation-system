@props([
    'id' => 'datatable',
    'columns' => [],
    'ajaxUrl' => '',
])

{{-- DataTables CSS --}}
@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.tailwindcss.min.css">
    @endpush
@endonce

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="min-w-full divide-y divide-gray-200" style="width:100%">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($columns as $column)
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            {{ $column['title'] ?? $column }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
    (function () {
        var columns = @json($columns);
        var mappedColumns = columns.map(function (col) {
            if (typeof col === 'string') {
                return { data: col, title: col };
            }
            return col;
        });

        $('#{{ $id }}').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ $ajaxUrl }}',
            columns: mappedColumns,
            responsive: true,
            language: {
                processing: '<div class="flex items-center gap-2 text-sm text-gray-500"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Loading...</div>',
            },
        });
    })();
</script>
@endpush
