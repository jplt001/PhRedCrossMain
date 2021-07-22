@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Blood Referral
			<small> View and generate Blood Referral reports</small>
		</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- FORM -->
        <div class="form-forecast-container">
            <div class="row">
                <div class="col-xs-9">
                    <input id="datepicker" name="date" type="text" class="form-control col-xs-4" placeholder="Select destination date" autofocus required>
                </div>                
                <div class="col-xs-3">
                    <button type="submit" class="btn btn-info btn-block btn-flat btn-submit">Generate</button>
                </div>
            </div>
        </div>
        <div class="preview-section text-center" style="width: 100%; display: none">
            <div class="preview-tools-container text-right" style="margin-bottom: 10px;">
                <form target="_blank" method="post" action="<?php echo URL::to('report/generatePdf'); ?>">
                    {{ csrf_field() }}
                    <button data-toggle="tooltip" title="Refresh" type="button" class="btn btn-primary report-refresh"><i class="fa fa-refresh"></i></button>
                    <input id="htmlHandle" type="hidden" name="htmlHandle" value="">
                    <input id="dateFinal" type="hidden" name="dateFinal" value="">
                    <input type="hidden" name="reportType" value="Blood Referral">
                    <button data-toggle="tooltip" title="Print" type="submit" class="btn btn-primary"><i class="fa fa-print"></i></button>
                </form>
            </div>
            <div class="preview-content"></div>
        </div>
        <!-- CONTENT -->
        
    </section>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
    $(document).ready(function(){
        $('#datepicker').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }); 
        $('.btn-submit').on('click', function(){
            $.ajax({
                type: "POST",
                url: "postGenerateReferralReport",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'date': $('#datepicker').val()
                },
                success: function(response){
                    console.log(response);
                    var html = "";
                    var header = ['No', 'Date', 'Name', 'Hospital', '# of Units', 'Blood Type', 'Address'];
                    var headerActivity = ['No', 'Date', 'Number of Blood Collected', '10%'];
                    var headerWidths = [20, 45, 120, 120, 50, 50, 100];
                    var headerWidthsActivity = [30, 75, 100, 50];

                    html += '<table style="width:40%;">' +
                                '<tr style="text-align:left;">' +
                                    '<td>' + 'Total accounted:' + '</td>' +
                                    '<td><b>' + response.accounted + '</b></td>' +
                                '</tr>' +
                                '<tr style="text-align:left;">' +
                                    '<td>' + 'Total redeemed:' + '</td>' +
                                    '<td><b>' + response.redeemed + '</b></td>' +
                                '</tr>' +
                                '<tr style="text-align:left;">' +
                                    '<td>' + 'Total remained:' + '</td>' +
                                    '<td><b>' + response.remained + '</b></td>' +
                                '</tr>' +
                                '<tr style="text-align:left;">' +
                                    '<td>&nbsp;</td>' +
                                    '<td>&nbsp;</td>' +
                                '</tr>' +
                            '</table>';
                            
                    /* GENERATE REFERRALS */
                    html += '<table width="100%" cellpadding="0" border="0"><tr><td width="70%" style="float:left">';
                    html += '<table class="tbl-report-temp-styled" style="width:100%">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < header.length; x++) {
                                                    html += '<th style="background-color: #ff6060; color: #fff;" width="' + headerWidths[x] + 'px">' + header[x] + '</th>';
                                                }
                                                html += '<th width="50px">&nbsp;</th>';
                    html += '</tr></thead><tbody>';

                    var i = 0;
                    response.res.forEach(function(row){
                        i++;
                        html += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html += '<td width="' + headerWidths[0] + 'px">' + i + '</td>';
                        html += '<td width="' + headerWidths[1] + 'px">' + row.date + '</td>';
                        html += '<td width="' + headerWidths[2] + 'px">' + row.patient_name + '</td>';
                        html += '<td width="' + headerWidths[3] + 'px">' + row.hospital_name + '</td>';
                        html += '<td width="' + headerWidths[4] + 'px">' + row.no_of_units + '</td>';
                        html += '<td width="' + headerWidths[5] + 'px">' + row.blood_type + '</td>';
                        html += '<td width="' + headerWidths[6] + 'px">' + row.address +'</td>';
                        html += '<td width="50px" style="background-color: rgba(0,0,0,0)">10%</td>';
                        html += '</tr>';
                    });

                    html += '</tbody></table></td><td width="30%" style="float:right">';

                    /* GENERATE ACTIVITIES */
                    html += '<table class="tbl-report-temp-styled" style="width:100%">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < headerActivity.length; x++) {
                                                    html += '<th style="background-color: #ff6060; color: #fff;" width="' + headerWidthsActivity[x] + 'px">' + headerActivity[x] + '</th>';
                                                }
                    html += '</tr></thead><tbody>';

                    var i = 0;
                    response.res_activity.forEach(function(row){
                        i++;
                        html += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html += '<td width="' + headerWidthsActivity[0] + 'px">' + i + '</td>';
                        html += '<td width="' + headerWidthsActivity[1] + 'px">' + row.date + '</td>';
                        html += '<td width="' + headerWidthsActivity[2] + 'px">' + row.no_of_blood_collected + '</td>';
                        html += '<td width="' + headerWidthsActivity[3] + 'px">' + Math.floor(row.ten_percent) + '</td>';
                        html += '</tr>';
                    });

                    html += '</tbody></table></td></tr></table>';

                    $('.preview-content').html(html);
                    $('#htmlHandle').val(html);
                    $('#dateFinal').val($('#datepicker').val());
                    $('.form-forecast-container').fadeOut(function(){
                        $('.preview-section').fadeIn();
                    });
                }
            });
        });      

        $('.report-refresh').on('click', function(){
            $('.preview-section').fadeOut(function(){
                $('.form-forecast-container').fadeIn();
            });
        });

    });
</script>
@endsection
