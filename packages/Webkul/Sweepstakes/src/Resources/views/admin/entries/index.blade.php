<x-admin::layouts>

@section('page_title')
    Sweepstakes Entries
@stop

@section('content')
    <div class="page-content">
        <h2 class="text-xl font-bold mb-4">Entries</h2>

        <table class="table-auto w-full border">
            <tr class="bg-gray-200">
                <th>ID</th>
                <th>Sweepstake</th>
                <th>Customer</th>
                <th>Entries</th>
                <th>Source</th>
                <th>Confirmed</th>
                <th>Date</th>
            </tr>

            @foreach($entries as $entry)
                <tr class="border-b">
                    <td>{{ $entry->id }}</td>
                    <td>{{ $entry->sweepstake_id }}</td>
                    <td>{{ $entry->customer_id }}</td>
                    <td>{{ $entry->entries }}</td>
                    <td>{{ $entry->source }}</td>
                    <td>{{ $entry->confirmed ? 'Yes' : 'No' }}</td>
                    <td>{{ $entry->created_at }}</td>
                </tr>
            @endforeach
        </table>

        {{ $entries->links() }}
    </div>
</x-admin::layouts>
