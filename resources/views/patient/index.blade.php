@extends('layouts.app')

@section('content')
<style type="text/css">
  td.details-control {
    background: url('<?php echo URL::asset('img/details_open.png'); ?>') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('<?php echo URL::asset('img/details_close.png'); ?>') no-repeat center center;
}
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>        
        Patient
        <small>View and manage patient </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-wheelchair" aria-hidden="true"></i> Patient</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      @if(\Session::has('success'))
        <div class="callout callout-success" id="alerting_user">
           {{ \Session::get('success')}}
        </div>
      @endif
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Patient</h3>

          <div class="box-tools pull-right">
            <button data-toggle="modal" data-target="#new-patient" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-wheelchair" aria-hidden="true"></i> Add New Patient</button>         
          </div>
        </div>
        <div class="box-body">
          <table id="tbl-patients" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th></th>
                  <th>#</th>
                  <th>Patient Name</th>
                  <th>Address</th>
                  <th>Contact Number</th>
                  <th>Status</th>
                  <th>reason</th>
                  <th>age</th>
                  <th>gender</th>
                  <th><small>ACTIONS</small></th>
                </tr>
                </thead>
              </table>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
    <!-- Modal -->
  <div id="denied-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Reason</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="<?php echo URL::to('patient/postDeny') ?>">
            {{ csrf_field() }}
            <input type="hidden" name="patient_id" id="patient_id">
            <div class="form-group">
              <label>Reason</label>
              <textarea class="form-control" style="resize: vertical;" name="reason" required=""></textarea>
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Submit</button>
          </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="new-patient" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">New Patient</h4>
        </div>
        <div class="modal-body">
          {!! Form::open(['url' => 'patient/create']) !!}
            <div class="form-group">
              {{ Form::label('first_name', 'First Name:', ['class' => 'text-info']) }}
              {{ Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. Juan', 'required'=>true]) }}
            </div>
            <div class="form-group">
              {{ Form::label('middle_name', 'Middle Name:', ['class' => 'text-info']) }}
              {{ Form::text('middle_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. Jimenez', 'required'=>true]) }}
            </div>
            <div class="form-group">
              {{ Form::label('last_name', 'Last Name:', ['class' => 'text-info']) }}
              {{ Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. De La Cruz', 'required'=>true]) }}
            </div>
            <div class="form-group">
              {{ Form::label('age', 'Age:', ['class' => 'text-info', 'required'=>true]) }}
              <input type="number" name="age" class="form-control" min="0" required="">
            </div>
            <div class="form-group">
              {{ Form::label('gender', 'Gender:', ['class' => 'text-info', 'required'=>true]) }}
              <select class="form-control" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="form-group">
              {{ Form::label('address', 'Address:', ['class' => 'text-info', 'required'=>true]) }}
              <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="form-group">
              {{ Form::label('contact_no', 'Contact Number:', ['class' => 'text-info']) }}
              {{ Form::text('contact_no', null, ['class'=>'form-control', 'placeholder'=>' e.g. 09123456789', 'max'=>12, 'required'=>true]) }}
            </div>
            <input type="hidden" name="added_by" value="{{$user_info->id}}">
        </div>
        <div class="modal-footer">
            {{ Form::submit('Save' , ['class'=>'btn btn-danger']) }}
          {!! Form::close() !!}
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="edit-patient" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Patient</h4>
        </div>
        <div class="modal-body">
          {!! Form::open(['url' => 'patient/update']) !!}
            <div class="form-group">
              {{ Form::label('edit_first_name', 'First Name:', ['class' => 'text-info']) }}
              <input type="text" name="first_name" id="edit_first_name" class="form-control">
            </div>
            <div class="form-group">
              {{ Form::label('middle_name', 'Middle Name:', ['class' => 'text-info']) }}
              {{ Form::text('middle_name', null, ['class'=>'form-control','id'=>'edit_middle_name' , 'placeholder'=>' e.g. Jimenez', 'required'=>true]) }}
            </div>
            <div class="form-group">
              {{ Form::label('last_name', 'Last Name:', ['class' => 'text-info']) }}
              {{ Form::text('last_name', null, ['class'=>'form-control', 'id'=>'edit_last_name' ,'placeholder'=>' e.g. De La Cruz', 'required'=>true]) }}
            </div>
            <div class="form-group">
              {{ Form::label('age', 'Age:', ['class' => 'text-info', 'required'=>true]) }}
              <input type="number" name="age"  id="edit_age" class="form-control" min="0" required="">
            </div>
            <div class="form-group">
              {{ Form::label('gender', 'Gender:', ['class' => 'text-info', 'required'=>true]) }}
              <select class="form-control" name="gender" id="edit_gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="form-group">
              {{ Form::label('address', 'Address:', ['class' => 'text-info', 'required'=>true]) }}
              <textarea name="address" id="edit_address" class="form-control"></textarea>
            </div>
            <div class="form-group">
              {{ Form::label('contact_no', 'Contact Number:', ['class' => 'text-info']) }}
              {{ Form::text('contact_no', null, ['class'=>'form-control', 'id'=>'edit_contact_no','placeholder'=>' e.g. 09123456789', 'max'=>12, 'required'=>true]) }}
            </div>            
            <input type="hidden" name="patient_id" id="edit_patient_id">
        </div>
        <div class="modal-footer">
            {{ Form::submit('Save' , ['class'=>'btn btn-danger']) }}
          {!! Form::close() !!}
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('custom_js')
<script type="text/javascript">
  setTimeout(function(){ $('#alerting_user').hide(200); }, 3000);
  function format ( d ) {
    var tmp_disp = '<h4>Patient Name:</h4><p class="lead text-info">'+d.patient_name+'<hr><label class="text-info">Address: </label> '+d.address+' <br><label class="text-info">Contact Number: </label> '+d.contact_no+' <br><label class="text-info">Status: </label> '+d.status+' <br> <label class="text-info">Age: </label>'+d.age+'<br> <label class="text-info">Gender: </label>'+d.gender+' <br>';
    
      // `d` is the original data object for the row
      // var tmp_disp =  '<table id="patient_details'+d.id+'">'+
      //     '<tr style="width: 260px;">'+
      //         '<td><label class="text-info">Patient Name:</label></td>'+
      //         '<td class="lead"> &nbsp;'+d.patient_name+'</td>'+
      //     '</tr>'+
      //     '<tr>'+
      //         '<td><label class="text-info">Address :</label></td>'+
      //         '<td>'+d.address+'</td>'+
      //     '</tr>'+
      //     '<tr>'+
      //         '<td><label class="text-info">Contact Number:</label></td>'+
      //         '<td>'+d.contact_no+'</td>'+
      //     '</tr>'+
      //         '<td ><label class="text-info">Status: </label></td>'+
      //         '<td>'+d.status+'</td>'+
      //     '</tr>';

      var pendings = '';
      var tmpStatus = d.status.replace("<span class='text-info'>", '')
      var tmpStatus = tmpStatus.replace("<span class='text-red'>", '');
      var tmpStatus = tmpStatus.replace("<span class='text-success'>", '');
      var status = tmpStatus.replace("</span>", '')
      
      if(status == 'Pending'){
        pendings = '<br><button class="btn btn-success btn-xs" onclick="approveAko('+"'info"+d.id+"'"+')">Approve</button>';
        pendings = pendings.concat(' &nbsp; <button onclick="denyAko('+d.id+')" class="btn btn-danger btn-xs">Deny</button></td>');
      }else if(status == 'Denied'){
        pendings = '<label class="text-info">Reason: </label> '+d.reason+' <br>';
      }
      disp = tmp_disp+ pendings+'</p>';
      return disp;
  }

  function approveAko(asd){
    // $('#'+asd).hide();
    var patient_ids = asd.replace('info', '');


    $.post('<?php echo URL::to('/patient/postApprove'); ?>', {_token: '<?php echo csrf_token(); ?>',patient_id: patient_ids }).done(function(){
      alert("Patient has been Approved...");
      
      location.reload();
      
    });
  }

  function denyAko(id){
    $('#patient_id').val(id);
    $('#denied-modal').modal();    
  }

  function deletesPatient(id){
    var r = confirm("Are you sure that you want delete this patient ?");
    if (r == true) {
        $.get('<?php echo URL::to('/patient/patient_delete'); ?>/'+id).done(function(){
          alert("Patient has been deleted.");
          location.reload();
        }).fail(function() {
          alert( "Ooppss, Sorry something wen't wrong. Please try again later." );
          location.reload();
        });
    } 
    
  }
  $(document).ready(function(){
    var table = $('#tbl-patients').DataTable({
      "ajax": "<?php echo URL::to('ajax/getPatients'); ?>",
      "columnDefs": [
        {"visible": false, "targets": 1 },
        {"visible": false, "targets": 6 },
        {"visible": false, "targets": 7 },
        {"visible": false, "targets": 8 }
      ],
      "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "id" },
            { "data": "patient_name" },
            { "data": "address" },
            { "data": "contact_no" },
            { "data": "status" },
            { "data": "reason" },
            { "data": "age" },
            { "data": "gender" },
            { "data": "actions" }
            // { "data": "status" }
        ],
        "paging": false,
      "scrollY": "280px",
      "scrollCollapse": true,
      "initComplete": function () {
          !isUserValid() && disableButtons();
      }
    });



    $('#tbl-patients tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
  });

  function editPatient(id){

    var settings = {
      "async": true,
      "crossDomain": true,
      "url": "{{ URL::to('ajax/getPatientInfo') }}/"+id,
      "method": "GET",
      "headers": {
        "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
        "cache-control": "no-cache",
        "postman-token": "96509386-6c2e-7384-0abf-544e290f8ac7"
      }
    }

    $.ajax(settings).done(function (response) {
      var data = JSON.parse(JSON.stringify(response));
      $('#edit_first_name').val(data.first_name);
      $('#edit_middle_name').val(data.middle_name);
      $('#edit_last_name').val(data.last_name);
      $('#edit_address').val(data.address);
      $('#edit_age').val(data.age);
      $('#edit_gender').val(data.gender);
      $('#edit_contact_no').val(data.contact_no);
      $('#edit_patient_id').val(data.id);
      

      $('#edit-patient').modal("show");
    }).fail(function(){
      alert("Oops, Something wen't wrong.");
    });
    
  }
</script>
@endsection