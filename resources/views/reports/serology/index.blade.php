@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Serology
			<small> Generate Serology reports</small>
		</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- FORM -->
        <div class="form-forecast-container">
            <div class="row">
                <div class="col-xs-3">
                    <select class="form-control" name="by">
                        <option value="extraction_date">Extraction Date</option>
                        <option value="date_released">Date Released</option>
                    </select>
                </div>
                <div class="col-xs-6">
                    <input id="datepicker" name="date" type="text" class="form-control col-xs-4" placeholder="Select destination date" autofocus required>
                </div>                
                <div class="col-xs-3">
                    <button type="button" class="btn btn-info btn-block btn-flat btn-submit">Generate</button>
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
    </section>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
    $(document).ready(function(){
        $('#datepicker').daterangepicker({timePicker: true, timePickerIncrement: 30, locale: { format: 'YYYY-MM-DD h:mm A'}});        

        $('.btn-submit').on('click', function(){
            $.ajax({
                type: "POST",
                url: "serology",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'by': $('select[name="by"]').val(),
                    'date': $('#datepicker').val()
                },
                success: function(response){
                    console.log(response);
                    var html1 = "", html2 = "", html3 = "", html4 = "";
                    var header1 = ['No', 'Extraction Date', 'Serial No', 'Org Name', 'Branch',
                                    'BT', 'Rh', 'Bag Used', 'Initial Assessment', 'Action Taken on Raw Unit',
                                    'Serology Result', 'PRBC Assessment', 'Total Unit Weight(g)', 'Final Volume(ml)', 'Action Taken(PC)',
                                    'FP Assessment', 'Total unit weight(g)', 'Final Volume(ml)', 'Action Taken (FP)'];
                    var header2 = ['Serial Number', 'Extraction Date',
                                    'Expiration Date', 'Days Remain (35 days)', 'Notice', 'Release Date',
                                    'Expiration Date', 'Days Remain (35 days)', 'Notice', 'Release Date',
                                    'Expiration Date', 'Days Remain (35 days)', 'Notice', 'Release Date',
                                    'Expiration Date', 'Days Remain (35 days)', 'Notice', 'Release Date',
                                    ];
                    var header3 = ['No', 'Serial No', 'Extraction Date', 'Source', 'Name of Organization',
                                    'Branch/Chapter', 'Bag Used', 'Bag Condition', 'Initial BT', 'Final BT',
                                    'Rh', 'ANTI-HIV MTD', 'ANTI-HIV RESULT', 'HBSAG MTD', 'HBSAG RESULT',
                                    'ANTI-HCV MTD', 'ANTI-HCV RESULT', 'SYPHILIS MTD', 'SYPHILIS RESULT', 'MALARIA MTD',
                                    'MALARIA RESULT', 'INITIAL REMARKS VALUE', 'HIV', 'HBS', 'HCV', 'SYP',
                                    'MAL'];
                    var header4 = ['Name of MBD', 'Chapter Branch', 'No', 'Serial No', 'Final BT',
                                    'Rh', 'ANTI-HIV', 'HBSAG', 'ANTI-HCV', 'SYPHILIS',
                                    'MALARIA', 'STATUS', 'Date Released: Whole Blood Only'];
                    var widths1 = [10, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40];
                    var widths2 = [40, 40, 40, 40, 50, 40, 40, 40, 50, 40, 40, 40, 50, 40, 40, 40, 50, 40];
                    var widths3 = [10, 40, 40, 40, 40, 40, 40, 40, 40, 40, 40, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 100, 20, 20, 20, 20, 20];
                    var widths4 = [60, 60, 20, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60];

                    /***************************** FIRST *****************************/
                    html1 += '<h1>Component Preperation Details</h1>';
                    html1 += '<table class="tbl-report-temp-styled">' + 
                                        '<thead>' +
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < header1.length; x++) {
                                                    html1 += '<th style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="' + widths1[x] + 'px">' + header1[x] + '</th>';
                                                }
                     html1 += '</tr></thead><tbody>';

                    var i = 0;
                    response.forEach(function(row){
                        i++;
                        html1 += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html1 += '<td width="' + widths1[0] + 'px">' + i + '</td>';
                        html1 += '<td width="' + widths1[1] + 'px">' + row.extraction_date + '</td>';
                        html1 += '<td width="' + widths1[2] + 'px">' + row.serial_no + '</td>';
                        html1 += '<td width="' + widths1[3] + 'px">' + 'N/A' + '</td>';
                        html1 += '<td width="' + widths1[4] + 'px">' + row.branch_name  + '</td>';
                        html1 += '<td width="' + widths1[5] + 'px">' + row.final_bt.substring(0, (row.final_bt.length - 1))  + '</td>';
                        html1 += '<td width="' + widths1[6] + 'px">' + (row.final_bt.substring((row.final_bt.length - 2), (row.final_bt.length - 1)) == '+' ? 'POS' : 'NEG')  + '</td>';
                        html1 += '<td width="' + widths1[7] + 'px">' + row.bag_used + '</td>';
                        html1 += '<td width="' + widths1[8] + 'px">-</td>';
                        html1 += '<td width="' + widths1[9] + 'px">-</td>';
                        html1 += '<td width="' + widths1[10] + 'px">-</td>';
                        html1 += '<td width="' + widths1[11] + 'px">-</td>';
                        html1 += '<td width="' + widths1[12] + 'px">-</td>';
                        html1 += '<td width="' + widths1[13] + 'px">-</td>';
                        html1 += '<td width="' + widths1[14] + 'px">-</td>';
                        html1 += '<td width="' + widths1[15] + 'px">-</td>';
                        html1 += '<td width="' + widths1[16] + 'px">-</td>';
                        html1 += '<td width="' + widths1[17] + 'px">-</td>';
                        html1 += '<td width="' + widths1[18] + 'px">-</td>';
                        html1 += '</tr>';
                    });

                    html1 += '</tbody></table>';

                    /***************************** SECOND *****************************/
                    html2 += '<h1>Daily Monitoring of Released Blood and Expiration Countdown</h1>';
                    html2 += '<table class="tbl-report-temp-styled">' + 
                                        '<thead style="text-align:center;">' + 
                                            "<tr style='text-align:center;'><th colspan='2' style='background-color: #ff6060; color: #fff;'></th>" +
                                            "<th colspan='4' style='border: 1px solid black; background-color: #ff6060; color: #fff;'>Whole Blood</th>" +
                                            "<th colspan='4' style='border: 1px solid black; background-color: #ff6060; color: #fff;'>Packed RBC</th>" +
                                            "<th colspan='4' style='border: 1px solid black; background-color: #ff6060; color: #fff;'>Platelet Concentrate</th>" +
                                            "<th colspan='4' style='border: 1px solid black; background-color: #ff6060; color: #fff;'>Frozen Plasma</th></tr>" +
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < header2.length; x++) {
                                                    html2 += '<th style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="' + widths2[x] + 'px">' + header2[x] + '</th>';
                                                }
                                                html2 += '</tr></thead><tbody>';

                    var i = 0;
                    response.forEach(function(row){
                        i++;
                        html2 += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html2 += '<td width="' + widths2[0] + 'px">' + row.serial_no + '</td>';
                        html2 += '<td width="' + widths2[1] + 'px">' + row.extraction_date + '</td>';
                        html2 += '<td width="' + widths2[2] + 'px">-</td>';
                        html2 += '<td width="' + widths2[3] + 'px">-</td>';
                        html2 += '<td width="' + widths2[4] + 'px">-</td>';
                        html2 += '<td width="' + widths2[5] + 'px">-</td>';
                        html2 += '<td width="' + widths2[6] + 'px">-</td>';
                        html2 += '<td width="' + widths2[7] + 'px">-</td>';
                        html2 += '<td width="' + widths2[8] + 'px">-</td>';
                        html2 += '<td width="' + widths2[9] + 'px">-</td>';
                        html2 += '<td width="' + widths2[10] + 'px">-</td>';
                        html2 += '<td width="' + widths2[11] + 'px">-</td>';
                        html2 += '<td width="' + widths2[12] + 'px">-</td>';
                        html2 += '<td width="' + widths2[13] + 'px">-</td>';
                        html2 += '<td width="' + widths2[14] + 'px">-</td>';
                        html2 += '<td width="' + widths2[15] + 'px">-</td>';
                        html2 += '<td width="' + widths2[16] + 'px">-</td>';
                        html2 += '<td width="' + widths2[17] + 'px">-</td>';
                        html2 += '</tr>';
                    });

                    html2 += '</tbody></table>';

                    /***************************** THIRD *****************************/
                    html3 += '<h1>Laboratory Results Input</h1>';
                    html3 += '<table class="tbl-report-temp-styled">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < (header3.length - 5); x++) {
                                                    if(header3[x] == 'INITIAL REMARKS VALUE'){
                                                        html3 += '<th rowspan="1" colspan="5" style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="100px">' + header3[x] + '</th>';
                                                    } else {
                                                        html3 += '<th rowspan="2" style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="' + widths3[x] + 'px">' + header3[x] + '</th>';
                                                    }
                                                }
                                                html3 += "</tr><tr>";
                                                for(var x = (header3.length - 5); x < header3.length; x++) {
                                                    html3 += '<th rowspan="1" style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="' + widths3[x] + 'px">' + header3[x] + '</th>';
                                                }
                                                html3 += '</tr></thead><tbody>';

                    var i = 0;
                    response.forEach(function(row){
                        i++;
                        html3 += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html3 += '<td width="' + widths3[0] + 'px">' + i + '</td>';
                        html3 += '<td width="' + widths3[1] + 'px">' + row.serial_no + '</td>';
                        html3 += '<td width="' + widths3[2] + 'px">' + row.extraction_date + '</td>';
                        html3 += '<td width="' + widths3[3] + 'px">' + row.source + '</td>';
                        html3 += '<td width="' + widths3[4] + 'px"> - </td>';
                        html3 += '<td width="' + widths3[5] + 'px">' + row.branch_name + '</td>';
                        html3 += '<td width="' + widths3[6] + 'px">' + row.bag_used + '</td>';
                        html3 += '<td width="' + widths3[7] + 'px">' + row.bag_condition + '</td>';
                        html3 += '<td width="' + widths3[8] + 'px">' + row.bag_condition + '</td>';
                        html3 += '<td width="' + widths3[9] + 'px">' + row.final_bt.substring(0, (row.final_bt.length - 1)) + '</td>';
                        html3 += '<td width="' + widths3[10] + 'px">' + (row.final_bt.substring((row.final_bt.length - 2), (row.final_bt.length - 1)) == '+' ? 'POS' : 'NEG')  + '</td>';
                        html3 += '<td width="' + widths3[11] + 'px">' + row.anti_hiv_mtd + '</td>';
                        html3 += '<td width="' + widths3[12] + 'px">' + row.anti_hiv_result + '</td>';
                        html3 += '<td width="' + widths3[13] + 'px">' + row.hbsag_mtd + '</td>';
                        html3 += '<td width="' + widths3[14] + 'px">' + row.hbssag_result + '</td>';
                        html3 += '<td width="' + widths3[15] + 'px">' + row.anti_hcv_mtd + '</td>';
                        html3 += '<td width="' + widths3[16] + 'px">' + row.anti_hcv_result + '</td>';
                        html3 += '<td width="' + widths3[17] + 'px">' + row.syphilis_mtd + '</td>';
                        html3 += '<td width="' + widths3[18] + 'px">' + row.syphilis_result + '</td>';
                        html3 += '<td width="' + widths3[19] + 'px">' + row.malaria_mtd + '</td>';
                        html3 += '<td width="' + widths3[20] + 'px">' + row.malaria_result + '</td>';
                        html3 += '<td width="' + widths3[22] + 'px">' + row.anti_hiv + '</td>';
                        html3 += '<td width="' + widths3[23] + 'px">' + row.hbsag + '</td>';
                        html3 += '<td width="' + widths3[24] + 'px">' + row.anti_hcv + '</td>';
                        html3 += '<td width="' + widths3[25] + 'px">' + row.syphilis + '</td>';
                        html3 += '<td width="' + widths3[26] + 'px">' + row.malaria + '</td>';
                        html3 += '</tr>';
                    });

                    html3 += '</tbody></table>';

                    /***************************** FOURTH *****************************/
                    html4 += '<h1>Serology Laboratory Results</h1>';
                    html4 += '<table class="tbl-report-temp-styled">' + 
                                        '<thead>' +
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < header4.length; x++) {
                                                    html4 += '<th style="border: 1px solid black; background-color: #ff6060; color: #fff;" width="' + widths4[x] + 'px">' + header4[x] + '</th>';
                                                }
                    html4 += '</tr></thead><tbody>';

                    var i = 0;
                    response.forEach(function(row){
                        i++;
                        html4 += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                        html4 += '<td width="' + widths4[0] + 'px">' + row.name_of_mbd + '</td>';
                        html4 += '<td width="' + widths4[1] + 'px">' + row.branch_name + '</td>';
                        html4 += '<td width="' + widths4[2] + 'px">' + i + '</td>';
                        html4 += '<td width="' + widths4[3] + 'px">' + row.serial_no + '</td>';
                        html4 += '<td width="' + widths4[5] + 'px">' + row.final_bt.substring(0, (row.final_bt.length - 1))  + '</td>';
                        html4 += '<td width="' + widths4[6] + 'px">' + (row.final_bt.substring((row.final_bt.length - 2), (row.final_bt.length - 1)) == '+' ? 'POS' : 'NEG')  + '</td>';
                        html4 += '<td width="' + widths4[7] + 'px">' + row.anti_hiv + '</td>';
                        html4 += '<td width="' + widths4[7] + 'px">' + row.hbsag + '</td>';
                        html4 += '<td width="' + widths4[7] + 'px">' + row.anti_hcv + '</td>';
                        html4 += '<td width="' + widths4[7] + 'px">' + row.syphilis + '</td>';
                        html4 += '<td width="' + widths4[7] + 'px">' + row.malaria + '</td>';
                        switch(row.status){
                            case 0:
                                html4 += '<td width="' + widths4[7] + 'px"><b>Pending</b></td>';
                                break;
                            case 1:
                                html4 += '<td width="' + widths4[7] + 'px"><b style="color: green">Passed</b></td>';
                                break;
                            case 2:
                                html4 += '<td width="' + widths4[7] + 'px"><b style="color: red">Failed</b></td>';
                                break;
                        }
                        html4 += '<td width="' + widths4[7] + 'px">' + row.date_released + '</td>';
                        html4 += '</tr>';
                    });

                    html4 += '</tbody></table>';

                    $('.preview-content').html(html1 + html2 + html3 + html4);
                    $('#htmlHandle').val(html1 + html2 + html3 + html4);
                    $('#dateFinal').val($('#datepicker').val());
                    $('.form-forecast-container').fadeOut(function(){
                        $('.preview-section').fadeIn();
                    });
                }
            });
        });      

    });
</script>
@endsection
