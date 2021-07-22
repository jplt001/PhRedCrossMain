@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Blood Bank
			<small> View and generate Blood Bank reports</small>
		</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- FORM -->
        <div class="form-forecast-container">
            <div class="row">
                <div class="form-group">
                    <div class="form-group">
                        <label style="margin-right: 20px;">
                            <input type="checkbox" class="minimal" name="chkbx-availability[]" value="ALL" autocomplete="off"> All
                        </label>
                        <label style="margin-right: 20px;">
                            <input type="checkbox" class="minimal" name="chkbx-availability[]" value="RESERVED" autocomplete="off"> Reserved
                        </label>
                        <label style="margin-right: 20px;">
                            <input type="checkbox" class="minimal" name="chkbx-availability[]" value="RELEASED" autocomplete="off"> Released
                        </label>
                        <label style="margin-right: 20px;">
                            <input type="checkbox" class="minimal" name="chkbx-availability[]" value="CANCELLED" autocomplete="off"> Cancelled
                        </label>
                    </div>
                </div>
                <div class="col-xs-9">
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
                    <input type="hidden" name="reportType" value="Blood Bank Reservations">
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
        $('#datepicker').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        }); 
        var isCheckedAll = true;
        $('input[type="checkbox"][name="chkbx-availability[]"]').on('click', function(){
            if(!this.checked){
                $('input[type="checkbox"][value="ALL"]').prop('checked', false);
            }
        });
        $('input[type="checkbox"][name="chkbx-availability[]"][value="ALL"]').on('click', function(){
            isCheckedAll = !isCheckedAll;
            checkUncheckAll();
        });
        function checkUncheckAll(){
            $('input[type="checkbox"][name="chkbx-availability[]"]').prop('checked', isCheckedAll);
        }

        $('.report-refresh').on('click', function(){
            $('.preview-section').fadeOut(function(){
                $('.form-forecast-container').fadeIn();
            });
        });

        function romanize (num) {
            if (!+num)
                return NaN;
            var digits = String(+num).split(""),
                key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
                    "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
                    "","I","II","III","IV","V","VI","VII","VIII","IX"],
                roman = "",
                i = 3;
            while (i--)
                roman = (key[+digits.pop() + (i * 10)] || "") + roman;
            return Array(+digits.join("") + 1).join("M") + roman;
        }

        $('.btn-submit').on('click', function(){
            var availabilities = [];
            $('input[type="checkbox"][name="chkbx-availability[]"]:checked').each(function (index) {
                availabilities.push($(this).val());
            });
            if($('input[type="checkbox"][name="chkbx-availability[]"]:checked').length > 0){
                $.ajax({
                    type: "POST",
                    url: "bloodbank/postReportBloodbank",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'date': $('#datepicker').val(),
                        'chkbx-availability': availabilities
                    },
                    success: function(response){
                        var html = '<table class="tbl-report-temp-styled">' + 
                                        '<thead>' + 
                                            '<tr style="text-align:center;">';
                        var maxComponents = 0;
                        var header = ['#', 'Branch', 'Reserved', 'Released', 'Availability', 'Name of Patient / HD', 'HOSPITAL', 'BT'];
                        var headerWidths = [20, 40, 75, 75, 45, 120, 80, 30];
                        response.forEach(function(row){
                            maxComponents = row.components.length > maxComponents ? row.components.length : maxComponents;
                        });
                        for(var x = 1; x <= maxComponents; x++){
                            header.push('COMPONENT ' + romanize(x));
                            headerWidths.push(60);
                        }
                        for(var x = 0; x < header.length; x++) {
                            html += '<th style="background-color: #ff6060; color: #fff;" width="' + headerWidths[x] + 'px">' + header[x] + '</th>';
                        }

                        html += '</tr></thead><tbody>';

                        var i = 0;
                        response.forEach(function(row){
                            i++;
                            html += '<tr style="text-align:center; background-color: ' + (i % 2 == 0 ? 'rgb(255, 255, 255)':'rgb(224, 235, 255)') + '">';
                            html += '<td width="' + headerWidths[0] + 'px">' + i + '</td>';
                            html += '<td width="' + headerWidths[1] + 'px">' + row.branch_name + '</td>';
                            html += '<td width="' + headerWidths[2] + 'px">' + row.time_reserved + '</td>';
                            html += '<td width="' + headerWidths[3] + 'px">' + row.time_released + '</td>';
                            html += '<td width="' + headerWidths[4] + 'px">' + row.availability + '</td>';
                            html += '<td width="' + headerWidths[5] + 'px">' + row.patient_name + '</td>';
                            html += '<td width="' + headerWidths[6] + 'px">' + row.hospital_name +'</td>';
                            html += '<td width="' + headerWidths[7] + 'px">' + row.blood_type + '</td>';
                            row.components.forEach(function(component){
                                html += '<td width="60px">' + component.code + ' x' + component.qty + '</td>';
                            });
                            if(row.components.length < maxComponents){
                                for(var j = 0; j < (maxComponents - row.components.length); j++){
                                    html += '<td width="60px"> - </td>';
                                }
                            }
                            html += '</tr>';
                        });

                        html += '</tbody></table>';
                        $('.preview-content').html(html);
                        $('#htmlHandle').val(html);
                        $('#dateFinal').val($('#datepicker').val());
                        $('.form-forecast-container').fadeOut(function(){
                            $('.preview-section').fadeIn();
                        });
                    }
                });
            } else {
                toastr.error("Please select an availability");
            }
        });      

        checkUncheckAll();
    });
</script>
@endsection
