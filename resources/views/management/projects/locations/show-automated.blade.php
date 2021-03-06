@extends('layouts.management')

@section('scripts')
    <script>
        $(function() {
            $('.locations-table').DataTable()
        })
    </script>

    <script type="text/javascript">
        $(function() {
            let data = [];

            function init() {
                // Load the Visualization API and the corechart package.
                google.charts.load('current', {'packages':['corechart']});

                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawCharts);
            }

            function drawCharts() {
                try {
                    console.log(JSON.parse('{!! $chartData !!}'));
                    data = createDataTable(JSON.parse('{!! $chartData !!}'), ['Gender', 'Age Group']);

                    drawPieChart();
                    drawBarChart();
                } catch (e) {
                    console.log(e);
                }
            }

            function createDataTable(rawData, $tableHeader) {
                try {
                    return google.visualization.arrayToDataTable([
                        $tableHeader,
                        ...rawData
                    ]);
                } catch (e) {
                    return null;
                }
            }

            function groupData(columnIndex) {
//                if (columnIndex === 1) {
//                    return google.visualization.data.group(data, [1], [{
//                        'column': 1,
//                        'aggregation': google.visualization.data.count,
//                        'type': 'number'
//                    }, {
//                        'column': 0,
//                        'aggregation': google.visualization.data.count,
//                        'type': 'number'
//                    }])
//                }

                return google.visualization.data.group(data, [columnIndex], [{
                    'column': columnIndex,
                    'aggregation': google.visualization.data.count,
                    'type': 'number'
                }])
            }

            function drawBarChart() {
                let ageData = groupData(1);

                let options = {
                    title: '',
                    width: '810',
                    height: '500',
                    chartArea: {width: '50%'},
                    colors: ['#FF7300', '#383A38', '#FFC799'],
                    hAxis: {
                        title: 'Age Groups',
                        minValue: 0
                    },
                    vAxis: {
                        title: 'Hits'
                    },
                    orientation: 'horizontal',
                    legend: { position: 'none' }
                };

                let chart = new google.visualization.BarChart(document.getElementById('age-graph'));
                chart.draw(ageData, options);
            }

            function drawPieChart() {
                let genderData = groupData(0);

                // Set chart options
                let options = {
                    title:'',
                    width: '810',
                    height: '500',
                    colors: ['#FF7300', '#383A38']
                };

                // Instantiate and draw our chart, passing in some options.
                let chart = new google.visualization.PieChart(document.getElementById('gender-graph'));
                chart.draw(genderData, options);
            }

            init();
        })
    </script>
@endsection

@section('content')
    <div class="info-section">
        <div class="info-title">
            <div class="col-sm-6" style="display: inline-flex;">
                <a href="/projects/{{ $project->id }}" class="nav-back"><i class="glyphicon glyphicon-chevron-left"></i></a>
                <h1 style="color: #fff">
                    {{ $project->name }}
                    <p class="info-sub-title">{{ $location->name }}</p>
                </h1>
            </div>
        </div>


        <div class="info-body">
            <a href="/projects/{{ $project->id }}/locations/{{ $location->id }}/event-reports"
               class="btn btn-primary">Verify Audit Report</a>
        </div>
    </div>


    @component('components.location-description',
        ['project' => $project, 'location' => $location, 'chartData' => $chartData])
    @endcomponent
{
    <div class="container-fluid" style="margin-top: -130px">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        @component('components.automated-chart', ['project' => $project, 'location' => $location])
                            @slot('nav')
                                <li class="{{ $services && in_array('manual', $services) ? '' : 'hide' }}">
                                    <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}">Manual</a>
                                </li>

                                <li class="{{ $services && in_array('automatic', $services) ? '' : 'hide' }} active">
                                    <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}/automated">Automated</a>
                                </li>

                                <li class="{{ $services && in_array('gps', $services) ? '' : 'hide' }}">
                                    <a href="/management/projects/update/{{ $project->id }}/locations/{{ $location->id }}/gps">GPS</a>
                                </li>

                                <li class="{{ count($videos) <= 0 ? 'hide' : '' }}">
                                    <a href="/management/projects/update/{{ $project['id'] }}/locations/{{ $location['id'] }}/videos">Video
                                    </a>
                                </li>
                            @endslot

                            @slot('title')
                            @endslot

                            @slot('ongoingReport')
                            @endslot

                            {{--@slot('timeandvideo')--}}
                            {{--@endslot--}}
                        @endcomponent


                        <h1>Raw Videos</h1>
                        <table class="table table-bordered locations-table">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th>Results</th>
                                <th>Status</th>
                                <th>Processed Time</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($raws as $raw)
                                <tr>
                                    <td>{{ $raw->file }}</td>
                                    <td>{{ $raw->results()->count() }}</td>
                                    <td>{{ $raw->status }}</td>
                                    <td>
                                        @if ($raw->status === 'completed')
                                            {{ \Carbon\Carbon::createFromTimestamp(strtotime($raw->completed_time))->diffInMinutes(\Carbon\Carbon::createFromTimestamp(strtotime($raw->processing_time))) }} Minutes
                                        @else
                                            Not Completed
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection