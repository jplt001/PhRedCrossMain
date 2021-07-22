// DSC5HHBAA
// line chart
var userType = $('#user').data('user-type');
var forecastPageLegendVisible = true;

function isUserValid(){
    return userType == 3 ? false : true;
}

function disableButtons(){
    $('.btn').remove();
}

function reservationPatientRefresh(){
    $('.modal-reservation-patient-mode-container').attr('hidden', 'hidden');
    $('.modal-reservation-select-patient-container').attr('hidden', 'hidden');
    $('.modal-reservation-new-patient-container').attr('hidden', 'hidden');
    $('.modal-reservation-blood-type-container').attr('hidden', 'hidden');
}

function reservationPatientExisting(){
    reservationPatientRefresh();
    $('.modal-reservation-patient-mode-container').removeAttr('hidden');
    $('.modal-reservation-select-patient-container').removeAttr('hidden');
}

function reservationPatientNew(){
    reservationPatientRefresh();
    $('.modal-reservation-patient-mode-container').removeAttr('hidden');
    $('.modal-reservation-new-patient-container').removeAttr('hidden');
    $('.modal-reservation-blood-type-container').removeAttr('hidden');
}

function reservationPatientEdit(){
    reservationPatientRefresh();
    $('.modal-reservation-select-patient-container').removeAttr('hidden');
}

var areaChartData = {
    labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'January', 'February', 'March', 'April', 'May', 'June', 'Julys'],
    datasets: [
        {
            label               : 'Electronics',
            fillColor           : 'rgba(60,141,188,0.9)',
            strokeColor         : 'rgba(60,141,188,0.8)',
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : [65, 59,1820, 821, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40]
        }
    ]
}

var module = window.location.pathname.split("/")[window.location.pathname.split("/").length - 1];

var lineChartColors = [
    {
        label: 'O+',
        color: 'rgba(60,141,188,0.9)',
        point: 'rgba(60,141,188,1)'
    },
    {
        label: 'O-',
        color: 'rgba(196, 223, 239,0.9)',
        point: 'rgba(196, 223, 239,1)'
    },
    {
        label: 'A+',
        color: 'rgba(196, 62, 89, 0.9)',
        point: 'rgba(196, 62, 89, 1)',
    },
    {
        label: 'A-',
        color: 'rgba(242, 155, 172, 0.9)',
        point: 'rgba(242, 155, 172, 1)',
    },
    {
        label: 'B+',
        color: 'rgba(67, 193, 95, 0.9)',
        point: 'rgba(67, 193, 95, 1)',
    },
    {
        label: 'B-',
        color: 'rgba(144, 229, 163, 0.9)',
        point: 'rgba(144, 229, 163, 1)',
    },
    {
        label: 'AB+',
        color: 'rgba(206, 184, 86, 0.9)',
        point: 'rgba(206, 184, 86, 1)',
    },
    {
        label: 'AB-',
        color: 'rgba(237, 220, 147, 0.9)',
        point: 'rgba(237, 220, 147, 1)',
    }
];

var forecastPatterns = [
    {
        name: "DAILY",
        value: 1
    },{
        name: "WEEKLY",
        value: 7
    },{
        name: "MONTHLY",
        value: 12
    },{
        name: "QUARTERLY",
        value: 90
    },{
        name: "ANNUAL",
        value: 1
    }
];

var monthFull = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

var componentSummary = ["A+", "A-", "AB+", "AB-", "B+", "B-", "O+", "O-"];

var component = [
    {
        id: 1,
        name: "Whole Blood",
        code: "WB,"
    },{
        id: 5,
        name: "B+",
    },{
        id: 1,
        name: "O+",
    },{
        id: 7,
        name: "AB+",
    },{
        id: 4,
        name: "A-",
    },{
        id: 6,
        name: "B-",
    },{
        id: 2,
        name: "O-",
    },{
        id: 8,
        name: "AB-",
    }
];

var bloodtypesArray = [
    {
        id: 3,
        name: "A+",
    },{
        id: 5,
        name: "B+",
    },{
        id: 1,
        name: "O+",
    },{
        id: 7,
        name: "AB+",
    },{
        id: 4,
        name: "A-",
    },{
        id: 6,
        name: "B-",
    },{
        id: 2,
        name: "O-",
    },{
        id: 8,
        name: "AB-",
    }
];

var PieData        = [
    {
        value    : 700,
        color    : '#f56954',
        highlight: '#f56954',
        label    : 'Chrome'
    },
    {
        value    : 500,
        color    : '#00a65a',
        highlight: '#00a65a',
        label    : 'IE'
    },
    {
        value    : 400,
        color    : '#f39c12',
        highlight: '#f39c12',
        label    : 'FireFox'
    },
    {
        value    : 600,
        color    : '#00c0ef',
        highlight: '#00c0ef',
        label    : 'Safari'
    },
    {
        value    : 300,
        color    : '#3c8dbc',
        highlight: '#3c8dbc',
        label    : 'Opera'
    },
    {
        value    : 100,
        color    : '#d2d6de',
        highlight: '#d2d6de',
        label    : 'Navigator'
    }
]

var areaChartOptions = {
    //Boolean - If we should show the scale at all
    showScale               : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : false,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - Whether the line is curved between points
    bezierCurve             : false,
    //Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot                : true,
    //Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    //Boolean - whether to make the chart responsive to window resizing
    responsive              : true
}

$('#pieChart, #dashboardPieChart').parent().css('pointer-events', 'none');

var pieOptions     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 2,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    tooltipFontSize: 11.5,
    animationSteps       : 100,
    //String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : true,
    //String - A legend template
    legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
}

var pieOptionsNew     = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    //String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    //Number - The width of each segment stroke
    segmentStrokeWidth   : 2,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    onAnimationComplete: function(){
        this.showTooltip(this.segments, true);
    },
    tooltipFontSize: 11.5,
    animationSteps       : 100,
    //String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : true,
    //String - A legend template
    legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
}


//-------------
//- BAR CHART -
//-------------
// var barChartCanvas                   = $('#barChart').get(0).getContext('2d');
// var barChart                         = new Chart(barChartCanvas);
// var barChartData                     = areaChartData;
// barChartData.datasets[1].fillColor   = '#00a65a';
// barChartData.datasets[1].strokeColor = '#00a65a';
// barChartData.datasets[1].pointColor  = '#00a65a';
var barChartOptions                  = {
    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
    scaleBeginAtZero        : true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    //String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    //Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    //Boolean - If there is a stroke on each bar
    barShowStroke           : true,
    //Number - Pixel width of the bar stroke
    barStrokeWidth          : 2,
    //Number - Spacing between each of the X value sets
    barValueSpacing         : 5,
    //Number - Spacing between data sets within X values
    barDatasetSpacing       : 1,
    //String - A legend template
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    //Boolean - whether to make the chart responsive
    responsive              : true,
    maintainAspectRatio     : true
};
// barChartOptions.datasetFill = false;
// barChart.Bar(barChartData, barChartOptions);

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$('.inventory-tab-header').html("<tr><th>Component</th><th>A+</th><th>B+</th><th>O+</th><th>AB+</th><th>A-</th><th>B-</th><th>O-</th><th>AB-</th></tr>");

var template_name = {
    "RESERVATION_SLIDER": "RESERVATION_SLIDER",
    "CENSUS_ROW": "CENSUS_ROW",
    "COMPONENT_INPUT": "COMPONENT_INPUT",
    "PATIENT_DROPDOWN": "PATIENT_DROPDOWN",
    "SEROLOGY_DROPDOWN": "SEROLOGY_DROPDOWN",
    "RESERVATION_ROW": "RESERVATION_ROW"
}

/* reservation slider template */
var getTemplate = function(templateName, data){
    var response = "";
    switch(templateName){
        case "RESERVATION_SLIDER":
            response = '<div class="slider" style="background-color:#fff; padding: 10px;">'+
                            '<div class="row">'+
                                '<div class="col-xs-6">'+
                                    '<table class="table table-bordered" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                                        '<thead style="background-color: #91d0e2; color: #fff">'+
                                            '<tr>'+
                                                '<th>Component</th>'+
                                                '<th>Units</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>';
                                            for(var x = 0; x < data.componentList.length; x++){
                                                response += '<tr>'+
                                                                '<td>' + data.componentList[x].code + '</td>'+
                                                                '<td>' + data.componentList[x].qty + '</td>'+
                                                            '</tr>';
                                            }
                                        response += '</tbody>'+
                                    '</table>'+
                                '</div>'+
                                '<div class="col-xs-6">'+
                                    '<form class="form">' +
                                        '<div class="col-xs-6 form-group">'+
                                            '<label>Diagnosis</label>'+
                                            '<p>' + (!data.diagnosis ? 'NA' : data.diagnosis) + '</p>'+
                                        '</div>'+
                                        '<div class="col-xs-6 form-group">'+
                                            '<label>Hospital</label>'+
                                            '<p>' + (!data.hospital ? 'NA' : data.hospital) + '</p>'+
                                        '</div>'+
                                        '<div class="col-xs-6 form-group">'+
                                            '<label>Date Added</label>'+
                                            '<p>' + (!data.dateAdded  ? 'NA' : data.dateAdded) + '</p>'+
                                        '</div>'+
                                        '<div class="col-xs-6 form-group">'+
                                            '<label>Remarks</label>'+
                                            '<p>' + (!data.remarks ? 'NA' : data.remarks) + '</p>'+
                                        '</div>'+
                                    '</form>' +
                                '</div>'+
                            '</div>'+
                        '</div>';
            break;
        case "CENSUS_ROW":
            var apositivepatients = obj.apositivepatients === null ? "" : obj.apositivepatients;
            response += "<tr>" +
                            "<td>" + obj.code + "</td>" +
                            "<td><span data-toggle='tooltip' " + (obj.apositivepatients && "title='" + obj.apositivepatients) + "'" +">" + obj.apositiveqty + "</span></td>" + 
                            "<td><span data-toggle='tooltip' " + (obj.bpositivepatients && "title='" + obj.bpositivepatients) + "'" +">" + obj.bpositiveqty + "</span></td>" +
                            "<td><span data-toggle='tooltip' " + (obj.opositivepatients && "title='" + obj.opositivepatients) + "'" +">" + obj.opositiveqty + "</span></td>" +
                            "<td><span data-toggle='tooltip' " + (obj.abpositivepatients && "title='" + obj.abpositivepatients) + "'" +">" + obj.abpositiveqty + "</span></td>" +
                            "<td><span data-toggle='tooltip' " + (obj.anegativepatients && "title='" + obj.anegativepatients) + "'" +">" + obj.anegativeqty + "</span></td>" +
                            "<td><span data-toggle='tooltip' " + (obj.bnegativepatients && "title='" + obj.bnegativepatients) + "'" +">" + obj.bnegativeqty + "</span></td>" + 
                            "<td><span data-toggle='tooltip' " + (obj.onegativepatients && "title='" + obj.onegativepatients) + "'" +">" + obj.onegativeqty + "</span></td>" +
                            "<td><span data-toggle='tooltip' " + (obj.abnegativepatients && "title='" + obj.abnegativepatients) + "'" +">" + obj.abnegativeqty + "</span></td>" +
                        "</tr>";
            break;
        case "COMPONENT_INPUT":
            data.forEach(function(obj) {
                response += "<tr>" + 
                                "<td>" + obj.component_label + "</td>" +
                                "<td>" + obj.qty + "</td>" +
                                "<td class='text-center'>" +
                                    "<button class='btn-delete-component btn btn-danger' data-id='" + obj.component_id + "'>" +
                                        "<i class='fa fa-minus'></i>" +
                                    "</button>" +
                                "</td>" + 
                            "</tr>";
            }, this);
            break;
        case "PATIENT_DROPDOWN":
            response = "<option selected value=''>-</option>";
            data.forEach(function(obj) {
                response += "<option value='" + obj.id + "'>" +
                                obj.last_name.toUpperCase() + ", " +
                                obj.first_name.toUpperCase() + " " +
                                (obj.middle_name == null ? "" : obj.middle_name.toUpperCase()) +
                            "</option>";
            }, this);
            break;
        case "RESERVATION_ROW":
            response = '<table class="table table-bordered table-hover" id="tblReservation">' +
                            '<thead>' +
                                '<tr>' +
                                    '<th>Branch</th>' +
                                    '<th>Time Reserved</th>' +
                                    '<th>Time Released</th>' +
                                    '<th>Patient Name</th>' +
                                    '<th>Blood Type</th>' +
                                    '<th>Availability</th>' +
                                    '<th>&nbsp;</th>' +
                                    '<th>&nbsp;</th>' +
                                    '<th>&nbsp;</th>' +
                                '</tr>' +
                            '</thead>' +
                        '</table>';
            break;
        case "SEROLOGY_DROPDOWN":
            response = "<option selected value=''>-</option>";
                        data.forEach(function(obj) {
                            response += "<option value='" + obj.id + "'>" + obj.serial_no + "</option>";
                        }, this);
            break;
        default:
            console.log("Invalid template name: " + templateName);
            console.info("Data:", data);
            break;
    }
    return response;
}