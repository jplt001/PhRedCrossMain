@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Blood Register
			<small> View and generate Blood Register reports</small>
		</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- FORM -->
        <div class="form-forecast-container">
            <div class="row">
                <div class="col-xs-4">
                    <select class="form-control" name="month">
                        <option value="1" {{ date('m') == 1 ? 'selected' : '' }}>January</option>
                        <option value="2" {{ date('m') == 2 ? 'selected' : '' }}>February</option>
                        <option value="3" {{ date('m') == 3 ? 'selected' : '' }}>March</option>
                        <option value="4" {{ date('m') == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" {{ date('m') == 5 ? 'selected' : '' }}>May</option>
                        <option value="6" {{ date('m') == 6 ? 'selected' : '' }}>June</option>
                        <option value="7" {{ date('m') == 7 ? 'selected' : '' }}>July</option>
                        <option value="8" {{ date('m') == 8 ? 'selected' : '' }}>August</option>
                        <option value="9" {{ date('m') == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ date('m') == 10 ? 'selected' : '' }}>October</option>
                        <option value="11" {{ date('m') == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ date('m') == 12 ? 'selected' : '' }}>December</option>
                    </select>
                </div>
                <div class="col-xs-4">
                    <select class="form-control" name="year">
                        @foreach($available_year as $k => $v)
                        <option value="{{ $v->years }}">{{ $v->years }}</option>
                        @endforeach
                    </select>
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
                    <button type="button" data-toggle="tooltip" title="Change" class="btn btn-primary report-refresh"><i class="fa fa-refresh"></i></button>
                    <input id="htmlHandle" type="hidden" name="htmlHandle" value="">
                    <input id="dateFinal" type="hidden" name="dateFinal" value="">
                    <input type="hidden" name="reportType" value="Blood Referral">
                    <button type="submit" data-toggle="tooltip" title="Print" class="btn btn-primary"><i class="fa fa-print"></i></button>
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
        $('#datepicker').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});     
        $('.btn-submit').on('click', function(){
            $.ajax({
                type: "POST",
                url: "blooddonor",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'year':  $('select[name="year"]').val(),
                    'month': $('select[name="month"]').val()
                },
                success: function(response){
                    console.log(response);
                    var htmlAccepted = "";
                    var htmlDeferred = "";
                    var headerAccepted = ['No', 'Date', 'Org', 'Status', 'Source', 'Category', 'D No per MBD', 'Name', 'Address', 'Contact No', 'Age', 'Gender', 'ABO', 'Rh', 'Serial No.', 'Blood Amt', '# of Times', 'Old', 'Regular'];
                    var headerDeffered = ['Date', 'Org', 'New/Old', 'Source', 'Category', 'D No per MBD', 'Name', 'Address', 'Contact No.', 'Age', 'Gender', 'Deferral Reason', 'Suggestion', 'Remarks'];
                    var headerAcceptedWidths = [10, 85, 40, 20, 60, 50, 60, 60, 60, 60, 20, 40, 40, 40, 45, 20, 20, 25, 25];
                    var headerDefferedrWidths = [85, 75, 20, 60, 50, 60, 60, 60, 60, 20, 20, 120, 45, 45];

                    htmlAccepted += '<h1>ACCEPTED</h1><table class="tbl-report-temp-styled">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < headerAccepted.length; x++) {
                                                    htmlAccepted += '<th style="background-color: #ff6060; color: #fff;" width="' + headerAcceptedWidths[x] + 'px;">' + headerAccepted[x] + '</th>';
                                                }
                    htmlAccepted += '</tr></thead><tbody>';

                    htmlDeferred += '<h1>DEFFERED</h1><table class="tbl-report-temp-styled">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                                                for(var x = 0; x < headerDeffered.length; x++) {
                                                    htmlDeferred += '<th style="background-color: #ff6060; color: #fff;" width="' + headerDefferedrWidths[x] + 'px;">' + headerDeffered[x] + '</th>';
                                                }
                    htmlDeferred += '</tr></thead><tbody>';

                    var i = 0;
                    response.forEach(function(row){
                        i++;
                        if(row.is_passed == 1){
                            htmlAccepted += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[0] + 'px">' + i + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[1] + 'px">' + row.donation_date + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[2] + 'px">' + 'N/A' + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[3] + 'px">' + (row.is_new == 1 ? 'NEW' : 'OLD')  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[4] + 'px">' + row.source  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[5] + 'px">' + row.category  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[6] + 'px">' + row.donor_no_per_mbd  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[7] + 'px">' + (row.last_name + ', ' + row.first_name + ' ' + row.middle_name) + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[8] + 'px">' + row.address  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[9] + 'px">' + row.cell_no  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[10] + 'px">' + row.age  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[11] + 'px">' + row.gender.toUpperCase()  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[12] + 'px">' + (row.abo ? row.abo : '-')  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[13] + 'px">' + row.rh  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[14] + 'px">' + row.serial_number  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[15] + 'px">' + row.no_bag_collected  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[16] + 'px">' + row.no_of_times  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[17] + 'px">' + (row.no_of_times >= 2 ? 'YES' : '')  + '</td>';
                            htmlAccepted += '<td width="' + headerAcceptedWidths[18] + 'px">' + (row.no_of_times <= 3 ? 'YES' : '')  + '</td>';
                            htmlAccepted += '</tr>';
                        } else {
                            htmlDeferred += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[0] + 'px">' + row.donation_date + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[1] + 'px">' + 'N/A' + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[2] + 'px">' + (row.is_new == 1 ? 'NEW' : 'OLD')  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[3] + 'px">' + row.source  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[4] + 'px">' + row.category  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[5] + 'px">' + row.donor_no_per_mbd  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[6] + 'px">' + (row.last_name + ', ' + row.first_name + ' ' + row.middle_name) + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[7] + 'px">' + row.address  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[8] + 'px">' + row.cell_no  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[9] + 'px">' + row.age  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[10] + 'px">' + row.gender  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[11] + 'px">' + row.reason  + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[12] + 'px">' + row.suggestion + '</td>';
                            htmlDeferred += '<td width="' + headerDefferedrWidths[13] + 'px">' + row.remarks + '</td>';
                            htmlDeferred += '</tr>';
                        }
                    });

                    htmlAccepted += '</tbody></table>';
                    htmlDeferred += '</tbody></table>';

                    $('.preview-content').html(htmlAccepted + htmlDeferred);
                    $('#htmlHandle').val(htmlAccepted + htmlDeferred);
                    $('#dateFinal').val($('select[name="month"]').val() + ' - ' + $('select[name="year"]').val());
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
