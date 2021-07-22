@extends('layouts.app')

@section('content')
<style type="text/css">
    .dataTables_empty{
        text-align: center;
    }
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Blood Referral
			<small> View and manage blood referral</small>
		</h1>
        <!-- <div class="section-tools text-right">
            <a href="#"><i class="fa fa-question-circle"></i> Help</a>
        </div> -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Blood Referral</h3>
                        <div class="pull-right">
                            <?php $res =  acl_roles( Auth::user()->user_type, 'blood_referral', 'create' ); ?>
                            @if( $res )
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-reservation"><i class="fa fa-plus"></i> Add New Referred Patients</button>
                            @endif
                            
                        </div>
                    </div>
                    <div class="box-body">

                        <table id="tbl_referred_patients" class="table table-hover">
                            <thead style="font-size: 11px; text-align: center;">
                                <th>Date</th>
                                <th>Name</th>
                                <th>Hospital</th>
                                <th># of Units</th>
                                <th>Blood Types</th>
                                <th>Address</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table">
                            <tr>
                                <td>Total accounted:</td>
                                <td id="total_accounted">0</td>
                            </tr>
                            <tr>
                                <td>Total redeemed:</td>
                                <td id="total_redeemed">0</td>
                            </tr>
                            <tr>
                                <td>Remained:</td>
                                <td id="remained">0</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header with boarderr">
                        <h3>Activity Held</h3>  
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-activity-held">Add Activity</button>                            
                        </div>                      
                    </div>
                    <div class="box-body">
                        <table id="tbl_activity_held" class="table table-hover">
                            <thead style="font-size: 11px; text-align: center;">
                                <th>No.</th>
                                <th>Date</th>
                                <th># of blood collected</th>
                                <th>10%</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-reservation">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="">New Referred Patients</h4>
                </div>
                <div class="modal-body"  style="height: 350px; overflow-y: auto;">
                  <div class="row">
                        <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">New Patient</a></li>
                      <li><a href="#tab_2" data-toggle="tab">Old Patient</a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">
                        <form action="{{ URL::to('blood_referral/saveNewPatientReferall') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Patient Type:</label>
                                {{ Form::select('patient_type', [0=>'Individual', 1=>'Group/Individual'], '', array('class'=>'form-control', 'id'=>'patient_type' , 'onchange'=>'showOrgNot()')) }}
                            </div>
                            <div class="form-group" id="orgs" hidden="">
                                <label>Organizations:</label>
                                {{ Form::select('organization', $organizations, '', array('class'=>'form-control select2', 'style'=>'width: 100%;') ) }}
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Patient Basic Information</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="first_name" class="form-control" placeholder="First Name"  required="">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label></label>
                                    </div>
                                    <div class="col-xs-6" data-toggle="buttons">
                                        
                                        @foreach($blood_types as $blood_type)
                                            <label class="btn btn-default btn-flat">
                                                <input type="radio" name="blood_types" value="{{ $blood_type->id }}" required="">
                                                <!-- <small> -->
                                                    <b>
                                                        {{ substr($blood_type->type, 0, (strlen($blood_type->type)-1)) }}
                                                        <sup>{{ substr($blood_type->type, (strlen($blood_type->type)-1)) }}</sup>
                                                    </b>
                                                <!-- </small> -->
                                            </label>
                                        @endforeach
                                    
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="number" placeholder="Age" id="modal-reservation-patient-age" class="form-control" required="">
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary">
                                                <input type="radio" name="reservation-gender" value="1">Male
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" name="reservation-gender" value="0">Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="form-group" id="hospital-form-group">
                                <label>Hospital</label>
                                {{ Form::select('hospital_id', $hospitals, '', array('class'=>'form-control select2', 'style'=>"width:100%;", 'id'=>'hospital_id')) }}
                            </div>
                            
                            <div class="form-group" id="no-of-units-form-group">
                                <label># of Units</label>
                                {{ Form::number('no_of_units', '', array('class'=>'form-control', 'min'=>1, 'id'=>'no_of_units', 'required'=> 'true')) }}
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="modal-reservation-input form-control" rows="3" style="resize: none" id="address" name="address" required=""></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" id="btnSaveReferral">Save</button>
                            </div>
                        </form>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <!-- nav-tabs-custom -->
                        <input type="hidden" id="_token" value="<?php echo csrf_token(); ?>">
                     
                        <div class="form-group" id="patient-form-group">
                            <label>Patient</label>
                            {{ Form::select('patient_id', $patients, '', array('class'=>'form-control select2', 'style'=>"width:100%;", 'id' => 'patient_id')) }}
                        </div>
                       
                        <div class="form-group" id="hospital-form-group">
                            <label>Hospital</label>
                            {{ Form::select('hospital_id', $hospitals, '', array('class'=>'form-control select2', 'style'=>"width:100%;", 'id'=>'hospital_id')) }}
                        </div>

                        <div class="form-group" id="no-of-units-form-group">
                            <label># of Units</label>
                            {{ Form::number('no_of_units', '', array('class'=>'form-control', 'min'=>1, 'id'=>'no_of_units' , 'required'=>'true')) }}
                        </div>
                        
                        <div class="form-group">
                            <label>Blood Type</label>
                            {{ Form::select('blood_type', $blood_type, '', array('class'=>'form-control', 'id'=>'blood_type')) }}
                            
                        </div>
                        
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="modal-reservation-input form-control" rows="3" style="resize: none" id="address" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="btnSaveReferral">Save</button>
                        </div>
                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-activity-held">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="">New Activity</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group" id="date-form-group">
                        <label>Date</label>
                        {{ Form::date('date', '', array('class'=>'form-control', 'id'=>'date')) }}
                    </div>
                    
                    <div class="form-group" id="no_of_blood_collected-form-group">
                        <label>Number of blood collected</label>
                        {{ Form::number('no_of_blood_collected', '', array('class'=>'form-control', 'min'=>1, 'id'=>'no_of_blood_collected')) }}
                    </div>                    
                    <div class="form-group">
                        <label>10%</label>
                        <input type="text" id="ten_percent" class="form-control" readonly>
                        <!-- {{ Form::text('ten_percent', '', array('class'=>'form-control', 'id'=>'ten_percent')) }} -->
                        
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSaveActivity">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
<script type="text/javascript">

    !isUserValid() && disableButtons();

  var tbl_referred_patients = $('#tbl_referred_patients').DataTable({
      "ajax": "<?php echo URL::to('ajax/getBloodReferrals'); ?>",
      "processing": false,
      "scrollY":        "346px",
      "scrollCollapse": true,
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": true,
      "scrollX": true,
      "columns":[
      { "data":"date"},
      { "data":"name"},
      { "data":"hospital_name"},
      { "data":"no_of_units"},
      { "data":"blood_type"},
      { "data":"address"},
      ]
    });

  var tbl_activity_held = $('#tbl_activity_held').DataTable({
      "ajax": "<?php echo URL::to('/ajax/getActivityHeld'); ?>",
      "processing": false,
      "scrollY":   "350px",
      "scrollCollapse": true,
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": true,
      "scrollX": true,
      "columns":[
      { "data":"no"},
      { "data":"date"},
      { "data":"no_of_blood_collected"},
      { "data":"ten_percent"}
      ]
    });

  setInterval (function test() {
    // tbl_referred_patients.ajax.reload();
    tbl_activity_held.ajax.reload();
    // tbl_referred_patients.draw();
}, 1000);



  setInterval(function(){
    $.get('<?php echo URL::to('/ajax/getAccountedRedemmed'); ?>', function(data){

        $('#total_accounted').text(data.total_accounted);
        $('#total_redeemed').text(data.total_redemmed);
        $('#remained').text(data.remaided);
        // console.log(data);
    });
  }, 10000);
  // $('#date').datepicker({
  //     autoclose: true
  //   });

  $('#no_of_blood_collected').keyup(function(){
        var inpTen_percent = $('#ten_percent');
        var totalCollected = $(this).val();
        var ten_percent = totalCollected * 0.10;
        inpTen_percent.val(ten_percent.toFixed(2));

  });

  $('#btnSaveReferral').click(function(){
        var  inpPatient_id          = $('#patient_id');
        var  inpHospital_idt        = $('#hospital_id');
        var  inpNo_of_units         = $('#no_of_units');
        var  inpBlood_type          = $('#blood_type');
        var  inpAddress             = $('#address');
        var patientFormGroup        = $('#patient-form-group');
        var hostpitalFormGroup      = $('#hospital-form-group');
        var noOfUnitsFormGroup      = $('#no-of-units-form-group');
        var from_organization_id    = $('#from_organization_id');

        if(inpPatient_id.val() == "" || inpPatient_id.val() == 0){
            patientFormGroup.addClass('has-error');
            patientFormGroup.append('<span id="patientError" class="help-block">Please enter the patient.</span>');    
        }else{
            patientFormGroup.removeClass('has-error');
            $('#patientError').remove();
        }
        if(inpHospital_idt.val() == "" || inpHospital_idt.val() == 1){
            $('#patientError').remove();
            hostpitalFormGroup.removeClass('has-error');
            hostpitalFormGroup.addClass('has-error');
            hostpitalFormGroup.append('<span id="patientError" class="help-block">Please select a patient.</span>');    
        }else{
            hostpitalFormGroup.removeClass('has-error');
            $('#patientError').remove();
        }        
        if(inpNo_of_units.val().length == 0){
            $('#noOfUnitsError').remove();
            noOfUnitsFormGroup.addClass('has-error');
            noOfUnitsFormGroup.append('<span id="noOfUnitsError" class="help-block">Please enter the number of unit taken.</span>');    
        }else if(inpNo_of_units.val() == 0){
            $('#noOfUnitsError').remove();
            noOfUnitsFormGroup.addClass('has-error');
            noOfUnitsFormGroup.append('<span id="noOfUnitsError" class="help-block">Please enter the number more than 0.</span>'); 
        }else{
            noOfUnitsFormGroup.removeClass('has-error');
            $('#noOfUnitsError').remove();
        }

        
        if(inpPatient_id.val().length >0 && inpHospital_idt.val().length >0 && inpNo_of_units.val().length >0 && inpBlood_type.val().length > 0 && inpAddress.val().length > 0){
            $.post('<?php echo URL::to('/blood_referral/savePatientReferral'); ?>', {"  ": $('#_token').val(),patient_id: inpPatient_id.val(), hospital_id: inpHospital_idt.val() , no_of_units: inpNo_of_units.val(), blood_type: inpBlood_type.val() , address: inpAddress.val(), from_organization_id:from_organization_id.val() }).done(function(){
                $('#modal-reservation').modal('toggle');
                
                inpPatient_id.val('');
                inpHospital_idt.val(1);
                inpNo_of_units.val('');
                inpBlood_type.val('');
                inpAddress.val('');
                // location.reload();
                // alert('Success');
            });

        }

  });

  $('#btnSaveActivity').click(function(){
    var date                            = $('#date');
    var dateFormGroup                   = $('#date-form-group');
    var from_organization_id            = $('#from_organization_id');

    var no_of_blood_collected           = $('#no_of_blood_collected');
    var ten_percent                     = $('#ten_percent');
    var noOfBloodCollectedFormGroup     = $('#no_of_blood_collected-form-group');

    if(date.val().length == 0){
        $('#selecDateError').remove();
        dateFormGroup.addClass('has-error');        
        dateFormGroup.append('<span id="selecDateError" class="help-block">Please enter the date when the activity held.</span>');
    }else{
        $('#selecDateError').remove();
        dateFormGroup.removeClass('has-error');
    }
    if(no_of_blood_collected.val().length == 0){
        $('#bloodCollectedError').remove();
        noOfBloodCollectedFormGroup.addClass('has-error');        
        noOfBloodCollectedFormGroup.append('<span id="bloodCollectedError" class="help-block">Please enter total blood collected.</span>');
    }else{
        $('#bloodCollectedError').remove();
        noOfBloodCollectedFormGroup.removeClass('has-error');
    }

    $.post('<?php echo URL::to('/blood_referral/saveActivityHeld'); ?>', { "_token": $('#_token').val(),date: date.val(), no_of_blood_collected:no_of_blood_collected.val(), ten_percent:ten_percent.val(), from_organization_id:from_organization_id.val()  }).done(function(){
        // $('#modal-activity-held').modal('toggle');
        location.reload();
    });
  });

  function showOrgNot(){
    var org = $('#orgs')
    var patient_type = $('#patient_type')

    if(patient_type.val() == '1'){
        org.removeAttr('hidden');
    }else{
        org.attr('hidden', true);
    }
  }
</script>
@endsection
 