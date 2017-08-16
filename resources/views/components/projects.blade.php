<table class="table table-hover projects-table">
    <thead>
    <tr>
        <th>Project Name</th>
        <th>Active Runs</th>
        <th>Completed Runs</th>
        <th>Target Hits</th>
        <th>Reported Hits</th>
        @if (Auth::user()->email !== 'domex@verify.com')
        <th>Verified Hits</th>
        @endif
        <th>Status</th>
    </tr>
    </thead>

    <tbody>

    @foreach ($projects as $project)
        <tr class="clickable" data-uri="/projects/{{ $project['id'] }}">
            <td>
                <strong>{{ $project['name'] }}</strong>
            </td>


            <td>
                {{ $project['active_runs'] }} / {{ $project['total_target_runs'] }}
            </td>


            <td>
                {{ $project['completed_runs'] }} / {{ $project['total_target_runs'] }}
            </td>


            <td>{{ number_format($project['total_target_hits'], 0, '.', ',') }}</td>


            <td>{{ number_format($project['reported_hits'], 0, '.', ',') }}</td>


            @if (Auth::user()->email !== 'domex@verify.com')
            <td>
                {{ $project['audited_hits'] }} (<span class="text-primary">{{ number_format($project['audit_percent'], 2) }}%</span>)
            </td>
            @endif

            <td>
                {{ ucwords($project['status']) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@section('scripts')
    @parent

    <script>
        $(function() {
            $('.projects-table').DataTable();
        });
    </script>
@endsection