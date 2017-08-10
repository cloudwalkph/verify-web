(function(verify, google, axios, $) {
    let c = {};

    let answers = [];
    let hits = [];
    let height;
    let projectId;

    let url;
    let demographicsUrl;

    let getHits = () => axios.get(url);
    let getDemographics = () => axios.get(demographicsUrl);

    c.init = (locationId) => {
        height = $('.panel-body').css('height');
        $('.overlay').css('height', height);

        projectId = $('#projectId').val();

        url = `/projects/${projectId}/locations/get-hits`;
        demographicsUrl = `/projects/${projectId}/locations/get-demographics`;

        if (typeof locationId !== 'undefined') {
            url = `/projects/${projectId}/locations/get-hits?location_id=${locationId}`;
            demographicsUrl = `/projects/${projectId}/locations/get-demographics?location_id=${locationId}`;
        }

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawCharts);

        getData();
    }

    function getData() {
        axios.all([getHits()]).then(
            axios.spread(function (hitsRes) {
                hits = hitsRes.data;

                buildAnswers(hits).then(response => {
                    answers = response;

                    if (hits.length > 0) {
                        drawCharts();
                    }

                    $('.overlay').addClass('hide');
                });
            }));
    }

    function buildAnswers(hits) {
        return new Promise((resolve, reject) => {
            let answers = [];

            for (let hit of hits) {
                for (let answer of hit.answers) {
                    answers.push(answer);
                }
            }

            resolve(answers);
        });
    }

    function drawCharts() {
        try {
            drawLineChart();
            drawPieChart();
            drawBarChart();
        } catch (e) {
            console.log(e);
        }
    }

    function createData(pollId, $tableHeader) {
        try {
            let arr = [];
            for (let answer of answers) {
                if (answer.poll_id != pollId) {
                    continue;
                }

                arr.push([answer.value, 1]);
            }

            let dt = google.visualization.arrayToDataTable([
                $tableHeader,
                ...arr
            ]);

            return google.visualization.data.group(dt, [0], [
                {
                    column: 1,
                    aggregation: google.visualization.data.sum,
                    type: 'number'
                }
            ]);
        } catch (e) {
            return null;
        }
    }

    function createDataForTimeline() {
        try {
            let arr = [];
            for (let hit of hits) {
                arr.push([new Date(hit.hit_timestamp), 1]);
            }

            let dt = google.visualization.arrayToDataTable([
                ['Time', 'Hits'],
                ...arr
            ]);

            return google.visualization.data.group(dt, [0], [
                {
                    column: 1,
                    aggregation: google.visualization.data.sum,
                    type: 'number'
                }
            ]);
        } catch(e) {
            return null;
        }
    }

    function drawBarChart() {
        let data = createData(1, ['Age Group', 'Hits']);

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
        chart.draw(data, options);
    }

    function drawPieChart() {
        let data = createData(2, ['Gender', 'Hits']);

        // Set chart options
        let options = {
            title:'',
            width: '810',
            height: '500',
            colors: ['#FF7300', '#383A38']
        };

        // Instantiate and draw our chart, passing in some options.
        let chart = new google.visualization.PieChart(document.getElementById('gender-graph'));
        chart.draw(data, options);
    }

    function drawLineChart() {
        let data = createDataForTimeline();

        let options = {
            title: '',
            curveType: 'function',
            height: '500',
            legend: {position: 'none'},
            colors: ['#FF7300'],
            explorer: {
                axis: 'horizontal',
                actions: ['dragToZoom', 'rightClickToReset']
            },
            vAxis: {
                minValue: 0
            },
            gridlines: { count: -1},
            library: {hAxis: { format: "hh. mm." } }
        };

        let chart = new google.visualization.LineChart(document.getElementById('time-graph'));

        let formatter = new google.visualization.DateFormat({formatType: 'long'});

        formatter.format(data, 0);

        chart.draw(data, options);
    }

    verify.Chart = c;

})(Verify || {}, google, axios, jQuery);