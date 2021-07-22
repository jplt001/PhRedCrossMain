$(document).ready(function(){
    !isUserValid() && disableButtons();
    
    if(module == "bloodbank"){

        ///////////////////////////////////////
        ///////// BLOOD BANK SCRIPTS //////////
        ///////////////////////////////////////
    
        /* declarations */
        var tblReservation;
        var componentList = [];
        var componentInputList = [];
        var componentDeleteList = [];
        var patientList = [];
        var reservation = {};
        var selectedTab = "RESERVED";
        var reservedBody = $('.inventory-tab-body-reserved');
        var releasedBody = $('.inventory-tab-body-released');
        var storageBody = $('.inventory-tab-body-storage');
        var inventoryList = $('#tblReservation tbody');
        var inputComponentId = $('#input-component-id');
        var inputComponentQty = $('#input-component-qty');
        var bloodbankPagination = {
            "reservation_id": "",
            "search_value": "",
            "page": 0,
            "max_rows": 10
        }
        
        /* census tab click */
        $('.inventory-tab').on('click', function(e){
            if(!$(this).hasClass('disabled-click') && !$(this).hasClass('active')){
                selectedTab = $(this).data("tabName");
                getCensus();
            }
        });

        /* get census ajax */
        var getCensus = function() {
            $('.inventory-tab').addClass('disabled-click');
            reservedBody.html(""); releasedBody.html(""); storageBody.html("");
            $.ajax({
                url: "bloodbank/getComponentList",
                type: "GET",
                success: function(result){
                    for(var x = 0; x < result[1].components.length; x++){
                        $('.inventory-tab-body-' + selectedTab.toLowerCase()).append("<tr class='census-row-" + x + "'>" +
                             "<td>" + result[1].components[x].code + "</td>" + "<td class='census-col-0'>-</td>" + "<td class='census-col-1'>-</td>" +
                             "<td class='census-col-2'>-</td>" + "<td class='census-col-3'>-</td>" + "<td class='census-col-4'>-</td>" +
                             "<td class='census-col-5'>-</td>" + "<td class='census-col-6'>-</td>" + "<td class='census-col-7'>-</td>" +
                        "</tr>");
                        for(var y = 0; y < result[0].bloodTypes.length; y++){
                            $.ajax({
                                url: "bloodbank/getCensus",
                                type: "GET",
                                data: { 'indexX': x, 'indexY': y, 'tab': selectedTab, 'bloodTypeId': bloodtypesArray[y].id, 'componentId': result[1].components[x].id },
                                success: function(result){
                                    $('.inventory-tab-body-' + result[0].i_availability.toLowerCase() + ' .census-row-' + result[0].i_indexX + ' .census-col-' + result[0].i_indexY).html(result[0].qty);
                                    if(result[0].i_indexX == 7){
                                        $('.inventory-tab').removeClass('disabled-click');
                                    }
                                }
                            });
                        }
                    }
                }
            });
        }
    
        /* reservation modal component add */
        $('#modal-reservation').on('click', '.btn-add-component', function(){
            inputComponentId.parent().removeClass('has-error');
            inputComponentId.parent().addClass(!inputComponentId.val() ? 'has-error' : '');
            if(inputComponentId.val()){
                var qty = inputComponentQty.val() ? inputComponentQty.val() : 1;
                var existingComponent = $.grep(componentInputList, function(component){
                    return component.component_id === parseInt(inputComponentId.val()); 
                })[0];
                existingComponent = $.grep(componentList, function(component){
                    return component.component_id === parseInt(inputComponentId.val()); 
                })[0];
                if(existingComponent){
                    inputComponentId.parent().addClass('has-error');
                    toastr.error('Selected component already exists');
                } else {
                    var newComponent = {
                        "component_label": $('#input-component-id option[value="' + inputComponentId.val() + '"]').text(),
                        "component_id": parseInt(inputComponentId.val()),
                        "qty": qty,
                        "isNew": true
                    };
                    componentInputList.push(newComponent);
                }
            }
            appendComponentInputList();
        });
    
        /* reservation modal component delete */
        $('#modal-reservation').on('click', '.btn-delete-component', function(){
            var selected = $(this).data('id');
            componentList = $.grep(componentList, function(component){
                if(component.component_id == selected){
                    componentDeleteList.push(component);
                    return component;
                }
            }, true);
            componentInputList = $.grep(componentInputList, function(component){
                if(component.component_id == selected){
                    return component;
                }
            }, true);
            appendComponentInputList();
        });
    
        /* create reservation */
        $('#modal-reservation').on('click', '#btn-modal-reservation-save', function(){
            reservation.serology = $('#modal-reservation-serology').val();
            reservation.diagnosis = $('#modal-reservation-diagnosis').val();
            reservation.patient_id = $('#modal-reservation-patient-id').val();
            reservation.lastname = $('#modal-reservation-patient-lastname').val();
            reservation.firstname = $('#modal-reservation-patient-firstname').val();
            reservation.middlename = $('#modal-reservation-patient-middlename').val();
            reservation.age = $('#modal-reservation-patient-age').val();
            reservation.gender = $('input[type="radio"][name="reservation-gender"]:checked').val();
            reservation.blood_type = $("input[name='blood_types']:checked").val();
            reservation.branch_id = $('#modal-reservation-branch-id').val();
            reservation.hospital_id = $('#modal-reservation-hospital-id').val();
            reservation.components = componentInputList;
            reservation.componentsDelete = componentDeleteList;
            reservation.remarks = $('#modal-reservation-remarks').val();
            reservation.patient_mode = $('input[type="radio"][name="patient-mode"]:checked').val();
            var url, msg;
            if($(this).data('title')){
                url = "bloodbank/updateReservation";
                msg = "Update successful";
            } else {
                url = "bloodbank/insertReservation";
                msg = "Reservation creation successful";
            }
    
            if(validateReservation()){
                $.ajax({
                    url: url,
                    type:"POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: reservation,
                    success:function(result){
                        tblReservation.ajax.reload();
                        toastr.success(msg);
                        $('#modal-reservation').modal('toggle');
                    },
                    error:function(error){
                        console.info('Error', error);
                    }
                });
            }
        }); 

        /* confirm reservation action */
        $('#modal-reservation-confirm').on('click', '.btn-continue-confirm', function(){
            var action = $(this).data('action');
            if(reservation.id){
                $.ajax({
                    url: "bloodbank/" + action + "Reservation",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: reservation,
                    success:function(result){
                        tblReservation.ajax.reload();
                        toastr.success(action + " successful");
                        $('#modal-reservation-confirm').modal('toggle');
                    },
                    error:function(error){
                        console.info('Error', error);
                    }
                });
            } else {
                toastr.error('No reservation selected');
            }
        });
    
        /* reservation modal patient mode listener */
        $('#modal-reservation').on('change', 'input[type="radio"][name="patient-mode"]', function(){
            var selected = $(this);
            selected.parent().removeClass('active');
            selected.prop('checked', true);
            selected.parent().addClass('active');
            switch(selected.val()){
                case "EXISTING":
                    reservationPatientExisting();
                    break;
                case "NEW":
                    reservationPatientNew();
                    break;
            }
        });

        /* refresh component list display */
        function appendComponentInputList(){
            $('.component-list-container').html(getTemplate(template_name.COMPONENT_INPUT, componentList.concat(componentInputList)));
        }
    
        /* reservation button listener */
        $('body').on('click', '.toggle-modal-reservation', function(e){
            e.stopPropagation();
            $('input[type="radio"][name="patient-mode"]').parent().removeClass('active');
            $('input[type="radio"][value="EXISTING"]').prop('checked', true);
            $('input[type="radio"][value="EXISTING"]').parent().addClass('active');
            if(!$(this).hasClass('disabled')){
                $(this).html($(this).data("title") && '<i class="fa fa-circle-o-notch fa-spin"></i>');
                toggleReservationModal($(this).data("title"), $(this).parent().parent().data("id"));
            }
        });
    
        /* show create/update reservation modal */
        var toggleReservationModal = function (action, id) {
            componentInputList = [];
            componentDeleteList = [];
            componentList = [];
            $('#modal-reservation .modal-title').text(action ? "Edit reservation" : "New reservation");
            $('#btn-modal-reservation-save').data('title', (action ? 'Edit' : ''));
            $('#btn-modal-reservation-save').text(action ? "Save Changes" : "Create");
            $('#modal-reservation-patient-id').select2();
            $('#modal-reservation-serology').select2();
            if(action){
                $.get("bloodbank/getInventory", { 'reservation_id': id })
                    .done(function(result) {
                        reservation.serology = result[0].serology;
                        reservation.diagnosis = result[0].diagnosis;
                        reservation.id = parseInt(result[0].id);
                        reservation.patient_id = result[0].patient_id;
                        reservation.blood_type = result[0].blood_type_id,
                        reservation.branch_id = result[0].branch_id,
                        reservation.hospital_id = result[0].hospital_id,
                        reservation.components = result[0].component_list,
                        reservation.remarks = result[0].remarks;
                        getPatients();
                        getSerology();
                        refreshReservationModal();
                        if(action == 'Edit'){
                            refreshReservationModal();
                            reservationPatientEdit();
                            $('#modal-reservation').modal('show');
                            if(action == 'Edit'){
                                $('button[data-title="' + action + '"]').html("<i class='fa fa-pencil'></i>");
                            } else {
                                $('button[data-title="' + action + '"]').html('i class="fa fa-plus"></i> Add New Reservation');
                            }
                        } else {
                            $('#modal-reservation-confirm .modal-dialog .modal-content .modal-body h4').html(action + " reservation?");
                            $('#modal-reservation-confirm .modal-dialog .modal-content .modal-body .btn-primary').data("action", action.toLowerCase());
                            $('#modal-reservation-confirm').modal('show');
                            var icon = "";
                            switch(action){
                                case "Release":
                                    icon = "check";
                                    break;
                                case "Cancel":
                                    icon = "ban";
                                    break;
                                case "Delete":
                                    icon = "times";
                                    break;
                            }
                            $('button[data-title="' + action + '"]').html("<i class='fa fa-" + icon + "'></i>");
                        }
                });
            } else {
                reservation = {};
                reservation.components = [];
                refreshReservationModal();
                getSerology();
                getPatients();
                reservationPatientExisting();
                $('#modal-reservation').modal('show');
            }
        };
    
        var refreshReservationModal = function(){
            $('#modal-reservation-serology').parent().removeClass('has-error');
            $('#modal-reservation-diagnosis').parent().removeClass('has-error');
            $('#modal-reservation-patient-id').parent().removeClass('has-error');
            $('#modal-reservation-patient-lastname').parent().removeClass('has-error');
            $('#modal-reservation-patient-firstname').parent().removeClass('has-error');
            $('#modal-reservation-patient-age').parent().removeClass('has-error');
            $('input[type="radio"][name="reservation-gender"]').parent().removeClass('btn-danger');
            $('input[type="radio"][name="reservation-gender"]').parent().addClass('btn-primary  ');
            $('.blood-type-buttons .btn').css('border-color', '#ddd');
            $('#modal-reservation-branch-id').parent().removeClass('has-error');
            $('#modal-reservation-hospital-id').parent().removeClass('has-error');
            $('#modal-reservation-remarks').parent().removeClass('has-error');
            inputComponentId.parent().removeClass('has-error');
            $("input[name='blood_types']").parent().removeClass('active');
            if(reservation.blood_type){
                var domSelectedBloodType = $("input[name='blood_types'][value='" + reservation.blood_type + "']");
                domSelectedBloodType.attr('checked', '');
                domSelectedBloodType.parent().addClass('active');
            } else {
                $("input[name='blood_types']").each(function(){
                    $(this).prop('checked', false);
                });
            }
            $("input[name='reservation-gender']").parent().removeClass('active');
            if(reservation.gender){
                var domSelectedGender = $("input[name='reservation-gender'][value='" + reservation.gender + "']");
                domSelectedGender.attr('checked', '');
                domSelectedGender.parent().addClass('active');
            } else {
                $("input[name='reservation-gender']").each(function(){
                    $(this).prop('checked', false);
                });
            }
            $('#modal-reservation-serology').html("<option selected value=''>-</option>");
            $('#modal-reservation-patient_id').html("<option selected value=''>-</option>");
            $('#modal-reservation-patient-lastname').val(reservation.lastname && reservation.lastname);
            $('#modal-reservation-patient-firstname').val(reservation.firstname && reservation.firstname);
            $('#modal-reservation-patient-middlename').val(reservation.middlename && reservation.middlename);
            $('#modal-reservation-patient-middlename').val(reservation.age && reservation.age);
            $('#modal-reservation-patient-age').val('');
            $('#modal-reservation-serology').val(reservation.serology && reservation.serology);
            $('#modal-reservation-diagnosis').val(reservation.diagnosis && reservation.diagnosis);
            $('#modal-reservation-branch-id').val(reservation.branch_id && reservation.branch_id);
            $('#modal-reservation-hospital-id').val(reservation.hospital_id && reservation.hospital_id);
            $('#input-component-id').val('');
            componentList = reservation.components;
            appendComponentInputList();
            $('#modal-reservation-remarks').val(reservation.remarks);
        }
    
        /* get patients */
        var getPatients = function() {
            $('#modal-reservation-patient_id').html("<option selected value=''>-</option>");
            $.get("patient/getPatients").done(function(result){
                $('#modal-reservation-patient-id').html(getTemplate(template_name.PATIENT_DROPDOWN, result));
                $('#modal-reservation-patient-id').val(reservation.patient_id && reservation.patient_id);
            });
        }

        /* get serology */
        var getSerology = function() {
            $('#modal-reservation-serology').html("<option selected value=''>-</option>");
            $.get("bloodbank/getAvailableSerology", {'serology_id': reservation.serology}).done(function(result){
                $('#modal-reservation-serology').html(getTemplate(template_name.SEROLOGY_DROPDOWN, result));
                $('#modal-reservation-serology').val(reservation.serology && reservation.serology);
            });
        }

        // bootstrap datatable server side  
        $('.box-reservation .box-body').html(getTemplate(template_name.RESERVATION_ROW));
        tblReservation = $('#tblReservation').DataTable({
            processing: true,
            serverSide: true,
            order: [[ 5, "asc" ]],
            ajax:{
                url :"bloodbank/getInventoryTable", // json datasource
                type: "get",  // method  , by default get
                dataFilter: function(data){
                    getCensus();                    
                    $('.toggle-modal-reservation').removeClass('disabled');
                    return data; // return JSON string
                }
            },
            createdRow: function(row, data, index) {
                $(row).addClass('bloodbank-row text-center');
                $(row).attr('data-diagnosis', data[data.length -6]);
                $(row).attr('data-hospital', data[data.length -5]);
                $(row).attr('data-date-added', data[data.length -4]);
                $(row).attr('data-id', JSON.stringify(data[data.length - 3]));
                $(row).attr('data-remarks', data[data.length - 2]); // 6 is index of column
                $(row).attr('data-component-list', JSON.stringify(data[data.length - 1]));
            },
            columnDefs: [
                {
                    "targets": [-6],
                    "render" : function (data, type, row) {
                        return "<b>" + data + "</b>";
                     }
                },{
                    "targets": [-1, -2, -3],
                    "orderable": false
                },{
                    "targets": -1,
                    "data": null,
                    "render" : function (data, type, row) {
                        if(isUserValid()){
                            if(data[5] == "RESERVED"){
                                return "<button data-title='Release' class='toggle-modal-reservation btn btn-success btn-xs'><i class='fa fa-check'></i></button>";
                            } else {
                                return "<button class='btn btn-success btn-xs disabled'><i class='fa fa-check'></i></button>";
                            }
                        } else {
                            return '';
                        }
                     }
                },{
                    "targets": -3,
                    "data": null,
                    "render" : function (data, type, row) {
                        if(isUserValid()){
                            if(data[5] == "RESERVED"){
                                return "<button data-title='Edit' class='toggle-modal-reservation btn btn-primary btn-xs'><i class='fa fa-pencil'></i></button>";
                            } else {
                                return "<button class='btn btn-primary btn-xs disabled'><i class='fa fa-pencil'></i></button>";
                            }
                        } else {
                            return '';
                        }
                     }
                },{
                    "targets": -2,
                    "data": null,
                    "render" : function (data, type, row) {
                        if(isUserValid()){
                            if(data[5] == "RESERVED"){
                                return "<button data-title='Cancel' class='toggle-modal-reservation btn btn-warning btn-xs'><i class='fa fa-ban'></i></button>";
                            } else {
                                return "<button data-title='Delete' class='toggle-modal-reservation btn btn-danger btn-xs'><i class='fa fa-times'></i></button>";
                            }
                        } else {
                            return '';
                        }
                     }
                },{
                    "targets": -4,
                    "data": null,
                    "render" : function (data, type, row) {
                        var icon = ""; var color = "";
                        switch(data[5]){
                            case "RESERVED":
                                icon = "floppy-o";
                                color = "red";
                                break;
                            case "RELEASED":
                                icon = "check";
                                color = "gray";
                                break;
                            case "CANCELLED":
                                icon = "ban";
                                color = "gray";
                                break;
                        }
                        return "<b class='text-" + color + "'><i class='fa fa-" + icon + "'></i> " + data[5] + "</b>"; 
                     }
                }
            ]
        });

        /* toggle reservation table row slider */
        $('body').on('click', '#tblReservation tbody tr', function () {
            var row = tblReservation.row($(this));
            if(row.child.isShown()){
                $('div.slider', row.child()).slideUp( function () {
                    row.child.hide();
                } );
            } else {
                row.child(getTemplate(template_name.RESERVATION_SLIDER, $(this).data()), 'no-padding').show();
                $('div.slider', row.child()).slideDown();
            }
        });
    
        /* validate reservation form */
        function validateReservation(){
            $('#modal-reservation-serology').parent().removeClass('has-error');
            $('.select2-selection.select2-selection--single').css('border', '1px solid #ccc');
            $('#modal-reservation-diagnosis').parent().removeClass('has-error');
            $('#modal-reservation-patient-id').parent().removeClass('has-error');
            $('#modal-reservation-patient-lastname').parent().removeClass('has-error');
            $('#modal-reservation-patient-firstname').parent().removeClass('has-error');
            $('#modal-reservation-patient-age').parent().removeClass('has-error');
            $('input[type="radio"][name="reservation-gender"]').parent().removeClass('btn-danger');
            $('input[type="radio"][name="reservation-gender"]').parent().addClass('btn-primary  ');
            $('.blood-type-buttons .btn').css('border-color', '#ddd');
            $('#modal-reservation-branch-id').parent().removeClass('has-error');
            $('#modal-reservation-hospital-id').parent().removeClass('has-error');
            $('#modal-reservation-remarks').parent().removeClass('has-error');
            inputComponentId.parent().removeClass('has-error');
            $("input[name='blood_types']").parent().removeClass('active');
            if(reservation.blood_type){
                var domSelectedBloodType = $("input[name='blood_types'][value='" + reservation.blood_type + "']");
                domSelectedBloodType.attr('checked', '');
                domSelectedBloodType.parent().addClass('active');
            } else {
                $("input[name='blood_types']").each(function(){
                    $(this).prop('checked', false);
                });
            }
            
            var result = true;
            if(!reservation.serology){
                $('#modal-reservation-serology').parent().addClass('has-error');
                $('.select2-selection.select2-selection--single').css('border', '1px solid #dd4b39');
                toastr.error('Please select serial number');
                result = false;
            }

            if(!reservation.diagnosis){
                $('#modal-reservation-diagnosis').parent().addClass('has-error');
                toastr.error('Please enter a diagnosis');
                result = false;
            }

            if($('input[type="radio"][name="patient-mode"]:checked').val() == "EXISTING"){
                if(!reservation.patient_id){
                    $('#modal-reservation-patient-id').parent().parent().children('label').css('color', '#dd4b39');
                    toastr.error('Please select a donor');
                    result = false;
                } 
            } else {
                if(!reservation.lastname){
                    $('#modal-reservation-patient-lastname').parent().addClass('has-error');
                    toastr.error('Please enter patient\'s lastname');
                    result = false;
                }
                if(!reservation.firstname){
                    $('#modal-reservation-patient-firstname').parent().addClass('has-error');
                    toastr.error('Please enter patient\'s firstname');
                    result = false;
                }
                if(!reservation.age || reservation.age < 1){
                    $('#modal-reservation-patient-age').parent().addClass('has-error');
                    toastr.error('Please enter patient\'s age');
                    result = false;
                }
                if(!reservation.gender){
                    $('input[type="radio"][name="reservation-gender"]').parent().removeClass('btn-primary');
                    $('input[type="radio"][name="reservation-gender"]').parent().addClass('btn-danger');
                    toastr.error('Please select patient\'s gender');
                    result = false;
                }
                if(!reservation.blood_type){
                    $('.blood-type-buttons .btn').css('border-color', '#dd4b39');
                    toastr.error('Please select a blood type');
                    result = false;
                }
            }
    
            if(!reservation.branch_id){
                $('#modal-reservation-branch-id').parent().addClass('has-error');
                toastr.error('Please select a branch');
                result = false;
            }
    
            if(!reservation.hospital_id){
                $('#modal-reservation-hospital-id').parent().addClass('has-error');
                toastr.error('Please select a hospital');
                result = false;
            }
    
            if(componentList.length == 0 && componentInputList.length == 0){
                inputComponentId.parent().addClass('has-error');
                toastr.error('Please select at least one(1) component');
                result = false;            
            }
    
            return result;
        }
    
        $('#btnHelp').on('click', function(){
            $('#modal-reservation-help').modal('show');
        });
    
        getCensus();
    }
    if(module == "forecast"){
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////// FORECASTING SCRIPTS /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
        var lineChartCanvas;
        var lineChart;
        var lineChartOptions = null;
        var pieChartCanvas = null;
        var bool = true;
        var pieChart = null;
        var chartPage = 1;
        var tblForecast;
        var chartRowCount = areaChartData.labels.length;
        var predict = 0;
        var view = "months";

        $('body').on('change', 'input[type="radio"][name="forecastType"]', function(){
            switch($(this).val()){
                case "MONTHLY":
                    view = "months";
                    break;
                case "ANNUAL":
                    view = "years";
                    break;
            }
            refreshDatepicker(view);
        });

        function refreshDatepicker(view){
            $('.forecast-datepicker-container').html('<input id="forecastDatepicker" type="text" class="form-control col-xs-4" placeholder="Select ' + view + '" autofocus required>');
            $.fn.datepicker.defaults.format = "yyyy-mm-dd";
            $('#forecastDatepicker').datepicker({
                autoclose: false,
                startDate: '+1' + view.charAt(0).toLowerCase(),
                clearBtn: true,
                showWeeks: true,
                calendarWeeks: true,
                startView: view, 
                minViewMode: view,
                maxViewMode: view
            });
        }
        
        refreshDatepicker(view);

        $(function () {
            changeForecastParameters = function (column) {
                $('.forecast-container').fadeOut('slow', function(){
                    $('#forecastGenerateButton').html('Generate');
                    $('#forecastGenerateButton').prop('disabled', false);
                    $('.form-forecast-container').fadeIn('slow', function() {
                        $('#forecastDatepicker').focus().select();
                    });
                });
            };
        });
    
        $(function () {
            forecastLineChartChangeLimit = function () {
                var selectedCount = $('input[name="chart-row-count"]:checked').val();
                chartRowCount = selectedCount != 'full' ? parseInt(selectedCount) : areaChartData.labels.length;
                chartPage = 1;
                refreshLineChart();
            };
        });
    
        $(function () {
            changePage = function (action) {
                var maxPage = Math.ceil(areaChartData.labels.length / chartRowCount);
                if(action == 'next'){
                    chartPage = chartPage == maxPage ? chartPage : chartPage + 1; 
                } else {
                    chartPage = chartPage == 1 ? chartPage : chartPage - 1; 
                }
                refreshLineChart(action);
            }
        });
    
        var refreshLineChart = function(){
            $('#lineChart').remove();
            $('.chart').append('<canvas id="lineChart" style="height:250px"></canvas>');
            $('.page-number').text(chartPage);
            var areaChartDataInstance = $.extend(true,{},areaChartData);
            var startRow = (parseInt(chartPage) - 1) * chartRowCount;
            var endRow = (startRow + parseInt(chartRowCount));
            areaChartDataInstance.labels = areaChartDataInstance.labels.slice(startRow, endRow);
            for(var x = 0; x < areaChartDataInstance.datasets.length; x++){
                areaChartDataInstance.datasets[x].data = areaChartDataInstance.datasets[x].data.slice(startRow, endRow);
            }
            // SET LINE CHART
            lineChartCanvas = $('#lineChart').get(0).getContext('2d');
            lineChart = new Chart(lineChartCanvas);
            barChartOptions.datasetFill = false;
            lineChart.Bar(areaChartData, barChartOptions);
        }
        
        $(function () {
            filterBloodType = function () {
                generateForecast();
            };
        });

        $('#forecastGenerateButton').on('click', function(){
            $('input[name="filter-blood-type"][value=0]').prop('checked', true);
            $('.forecast-filter-blood-type-container > .btn').removeClass('active');
            $('.forecast-filter-blood-type-container > .btn:first-child()').addClass('active');
            generateForecast();
        });

        function generateForecast(){
            var selectedBloodType = $("input[name='filter-blood-type']:checked").val();
            var dateDom = $('#forecastDatepicker'), typeDom = $('input[type="radio"][name="forecastType"]:checked');
            dateDom.css('border-color', dateDom.val() == '' ? 'red' : '#75B6E0');
            var selectedPattern = $.grep(forecastPatterns, function(e){return e.name == typeDom.val()})[0];
            $('.summary-pattern-container').html(selectedPattern.name == "MONTHLY" ? "Month" : "Year");
            if(dateDom.val() == ''){
                dateDom.css('border-color', 'red');
                dateDom.focus();
            } else {
                var date = new Date(Date.parse(dateDom.val()));
                if (date.getMonth() == 11) {
                    var selectedDate = new Date(date.getFullYear() + 1, 0, 1);
                } else {
                    var selectedDate = new Date(date.getFullYear(), date.getMonth() + 1, 1);
                }
                var today = new Date();
                var dateFrom = today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();
                selectedDate.setDate(selectedDate.getDate()-1);
                dateDom.css('border-color', '#75B6E0');
                $('.forecast-info-type').text(typeDom.val().toLowerCase());
                $('.forecast-info-enddate').text(selectedDate.toDateString());
                $('#forecastGenerateButton').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#forecastGenerateButton').prop('disabled', true);
                $.get("forecast/getForecast", {'date_input': dateDom.val(), 'chart_pattern': selectedPattern.value, 'blood_type': selectedBloodType})
                    .done(function(result) {
                        try {
                            if(result == "Division by zero"){
                                toastr.error("Insufficient reference data");
                                $('#forecastGenerateButton').html('Generate');
                                $('#forecastGenerateButton').prop('disabled', false);
                            } else {
                                var summaryBody = "";
                                var totalQty = 0;
                                areaChartData = {};
                                PieData = [];
                                forecastTableData = [];
                                areaChartData.labels = result.labels;
                                areaChartData.datasets = [];
                                for(var x = 0; x < result.data.length; x++){
                                    // round down decimals
                                    for(var z = 0; z < result.data[x].length; z++){
                                        if(!isNaN(result.data[x][z])){
                                            result.data[x][z] = Math.floor(result.data[x][z]);
                                            result.data[x][z] = result.data[x][z] < 0 ? 0 : result.data[x][z];
                                        }
                                    }

                                    // linechart
                                    var obj = {
                                        label               : lineChartColors[x].label,
                                        fillColor           : lineChartColors[x].color,
                                        strokeColor         : lineChartColors[x].color,
                                        pointColor          : lineChartColors[x].point,
                                        pointStrokeColor    : lineChartColors[x].point,
                                        pointHighlightFill  : '#fff',
                                        pointHighlightStroke: lineChartColors[x].point,
                                        data                : result.data[x]
                                    }

                                    areaChartData.datasets.push(obj);
    
                                    // datatable
                                    var total = 0;
                                    for(var y = 0; y < result.data[x].length; y++){
                                        total += result.data[x][y];
                                        var forecastTableRow = {
                                            bloodType: lineChartColors[x].label,
                                            dayOfWeek: areaChartData.labels[y],
                                            value: result.data[x][y]
                                        }
                                        forecastTableData.push(forecastTableRow);
                                        if(y == (result.data[x].length - 1)){
                                            summaryBody += "<tr>" + 
                                                                "<td>" + lineChartColors[x].label +"</td>" + 
                                                                "<td>" + (selectedPattern.name == "MONTHLY" ? monthFull[selectedDate.getMonth()] : selectedDate.getFullYear()) + "</td>" + 
                                                                "<td class='reason-column-" + x + "'></td>" + 
                                                                "<td>" + result.data[x][y] +"</td>" + 
                                                        + "</tr>";
                                        }
                                    }
                                    totalQty += total;
                                    $('.summary-body').append(summaryBody);
                
                                    // pie chart
                                    var pieObj = {
                                        value    : total,
                                        color    : lineChartColors[x].color,
                                        highlight: lineChartColors[x].point,
                                        label    : lineChartColors[x].label
                                    }
                                    PieData.push(pieObj);
                                }


                                $('.summary-body').html(summaryBody);
    
                                $('.form-forecast-container').fadeOut('slow', function(){
                                    $('.forecast-container').fadeIn('slow');
                                    
                                    $('#lineChart').remove();
                                    $('.chart').append('<canvas id="lineChart" style="height:250px"></canvas>');

                                    $('#pieChart').remove();
                                    $('.pie-chart').append('<canvas id="pieChart" style="height: 250px" height="250"></canvas>');
                                    
                                    // SET LINE CHART
                                    lineChartCanvas = $('#lineChart').get(0).getContext('2d');
                                    lineChart = new Chart(lineChartCanvas);
                                    barChartOptions.datasetFill = false;
                                    lineChart.Bar(areaChartData, barChartOptions);

                                    
                                    // SET LEGEND
                                    for(var x = 0; x < PieData.length; x++){
                                        console.log(PieData[x]);
                                        PieData[x].label = PieData[x].label + ' (' + Math.round((PieData[x].value / totalQty) * 100) + '%)';
                                    }

                                    if(forecastPageLegendVisible){
                                        var str = '<div class="text-center">';
                                        for(var x = 0; x < PieData.length; x++){
                                            str += '<span class="text-center" style="margin-top: 10px; margin-right: 10px; color: #fff; display:inline-block;height: 20px; width: 70px; background-color:'+PieData[x].color+'">'+
                                                        PieData[x].label +
                                                    '</span>';
                                            str += x == 3 ? '</br>' : '';
                                        }
                                        str += '</div>';
                                        $('.pie-chart').append(str);
                                    }

                                    // SET PIE CHART
                                    pieChartCanvas = $('#pieChart').get(0).getContext('2d');
                                    pieChart = new Chart(pieChartCanvas);
                                    pieChart.Pie(PieData, pieOptionsNew);


                                    // SET TABLE
                                    var toAppend = "";
                                    $.each(forecastTableData, function(key, val) {
                                        toAppend += "<tr>" + 
                                                        "<td>" + val.bloodType + "</td>" +
                                                        "<td>" + val.dayOfWeek + "</td>" +
                                                        "<td>" + val.value + "</td>" +
                                                    "</tr>"
                                    });
                                    $('#tblForecast').DataTable().destroy();
                                    $('.forecast-data-table').html(toAppend);
                                    $('#tblForecast').DataTable();
                                    $('#forecastGenerateButton').html('Generate');
                                    $('#forecastGenerateButton').prop('disabled', false);

                                    for(var x = 1; x < 9; x++){
                                        $.get("forecast/getForecastReason", { 'date_input': dateDom.val(), 'chart_pattern': selectedPattern.value, 'blood_type': x})
                                                .done(function(result) {
                                                    var response = result[0].split('-');
                                                    var textClass = response[0] == "NULL" ? "gray" : "";
                                                    $('.reason-column-' + result[0].split('-')[1]).html("<b class='text-" + textClass + "'>" + result[0].split('-')[0]) + "</b>";
                                        });
                                    }

                                });
                            }
                        } catch(err) {
                            console.log(err.message);
                        }
                    }
                );
            }
        }

        //add mock data every 2 minutes
        // setInterval(function(){
        //     console.log('Request sent');
        //     $.get("forecast/generateData")
        //         .done(function(result) {
        //             console.info('Response received',result);
        //         }
        //     );
        // }, 20000);
        
    }
});