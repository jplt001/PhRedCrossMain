@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Forecast
			<small> View and generate forecasting reports</small>
		</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- FORM -->
        <div class="form-forecast-container">
            <div class="row">
                <div class="col-xs-3">
                    <div class="btn-group colors" data-toggle="buttons">
                        <label class="btn btn-primary btn-flat active">
                            <input type="radio" name="forecastType" value="MONTHLY" autocomplete="off" checked> Monthly
                        </label>
                        <label class="btn btn-primary btn-flat">
                            <input type="radio" name="forecastType" value="ANNUAL" autocomplete="off"> Yearly
                        </label>
                    </div>
                </div>
                <div class="col-xs-6 forecast-datepicker-container">
                    <input id="forecastDatepicker" type="text" class="form-control col-xs-4" placeholder="Select month" autofocus required>
                </div>
                <div class="col-xs-3">
                    <button id="forecastGenerateButton" class="btn btn-info btn-block btn-flat">Generate Forecast</button>
                </div>
            </div>
        </div>
        <!-- CONTENT -->
        <div class="forecast-container">
            <div class="forecast-info text-center" onclick="changeForecastParameters('type');">
                <h4>
                    Showing
                    <a href="#" onclick="changeForecastParameters('type');" class="forecast-info-link">
                        <b><span class="forecast-info-type">type</span></b>
                    </a>
                    results from today to
                    <a href="#" onclick="changeForecastParameters('date');" class="forecast-info-link">
                        <b><span class="forecast-info-enddate">enddate</span></b>
                    </a>
                </h4>
            </div> 
            <div class="row">
                <div class="col-md-12">
                    <div class="form-inline">
                        <div class="form-group pull-left">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary btn-flat active">
                                    <input type="radio" name="chart-row-count" value="full" autocomplete="off" onchange="forecastLineChartChangeLimit()" checked> Full
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="chart-row-count" value="10" autocomplete="off" onchange="forecastLineChartChangeLimit()"> 10
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="chart-row-count" value="5" autocomplete="off" onchange="forecastLineChartChangeLimit()"> 5
                                </label>
                            </div>
                        </div>
                        <div class="form-group pull-right">
                            <div class="btn-group forecast-filter-blood-type-container" data-toggle="buttons">
                                <label class="btn btn-primary btn-flat active">
                                    <input type="radio" name="filter-blood-type" value="0" autocomplete="off" onchange="filterBloodType()" checked> All
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="3" autocomplete="off" onchange="filterBloodType()"> A+
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="5" autocomplete="off" onchange="filterBloodType()"> 	B+
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="1" autocomplete="off" onchange="filterBloodType()"> O+
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="7" autocomplete="off" onchange="filterBloodType()"> 	AB+
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="4" autocomplete="off" onchange="filterBloodType()"> A-
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="6" autocomplete="off" onchange="filterBloodType()"> 	B-
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="2" autocomplete="off" onchange="filterBloodType()"> O-
                                </label>
                                <label class="btn btn-primary btn-flat">
                                    <input type="radio" name="filter-blood-type" value="8" autocomplete="off" onchange="filterBloodType()"> 	AB-
                                </label>
                            </div>
                        </div>
                    </div><br><br><br>
                    <div class="chart">
                        <canvas id="lineChart" style="height:250px"></canvas>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-flat" onclick="changePage('prev')">Previous</button>
                        <label class="page-number"></label>
                        <button class="btn btn-primary btn-flat" onclick="changePage('next')">Next</button>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs text-right ui-sortable-handle">
                            <li class="header"><i class="fa fa-line-chart"></i> Forecast Summary</li>
                            <li class="forecast-tab active" data-tab-name="SUMMARY"><a href="#forecast-summary" data-toggle="tab" aria-expanded="false">Summary</a></li>
                            <li class="forecast-tab" data-tab-name="DETAILS"><a href="#forecast-details" data-toggle="tab" aria-expanded="true">Details</a></li>
                        </ul>
                        <div class="tab-content" style="min-height: 517px">
                            <div class="tab-pane active" id="forecast-summary">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Blood Type</th>
                                            <th class="summary-pattern-container"></th>
                                            <th>Diagnosis</th>
                                            <th>Released Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="summary-body">
                                        <tr>
                                            <td>A+</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>A-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>AB+</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>AB-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>B+</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>B-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>O+</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>O-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="forecast-details">
                                <table class="table" id="tblForecast">
                                    <thead>
                                        <tr>
                                            <th>Blood Type</th>
                                            <th class="summary-pattern-container"></th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="forecast-data-table"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 pie-chart">
                    <canvas id="pieChart" style="height: 250px" height="250"></canvas>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
