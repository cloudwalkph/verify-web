<table class="table table-hover locations-table">
    <thead>
    <tr>
        <th>Id</th>
        <th>Project Type</th>
        <th>Location Name</th>
        <th>Date</th>
        <th>Target Hits</th>
        <th>Reported Hits</th>
        @if (Auth::user()->email !== 'domex@verify.com')
        <th>Audited Hits</th>
        @endif
        <th>Status</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($locations as $location)
        <tr class="clickable" data-uri="/projects/{{ $location['project_id'] }}/locations/{{ $location['id'] }}">
            <td>
                <strong>{{ $location['id'] }}</strong>
            </td>

            <td>
                <strong>{{ $location['project_type'] }}</strong>
            </td>

            <td>
                <strong>{{ $location['name'] }}</strong>
            </td>

            <td>
                {{ $location['date'] }}
            </td>

            <td>
                {{ $location['target_hits'] > 0 ? $location['target_hits'] : 'NA' }}
            </td>

            <td>
                {{ $location['reported_hits'] > 0 ? $location['reported_hits'] : 'NA' }}
            </td>

            @if (Auth::user()->email !== 'domex@verify.com')
            <td>
                {{ $location['audited_hits'] }} (<span class="text-primary">{{ number_format($location['audit_percent'], 2) }}%</span>)
            </td>
            @endif

            <td>
                {{ ucwords($location['status']) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@section('scripts')
    @parent

    <script>
        $(function() {
            $('.locations-table').DataTable( {
                "order": [[ 3, "desc" ]]
            } );
        });
    </script>
@endsection