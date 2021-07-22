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
        Hospital
        <small> View and manage hospital</small>        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-hospital-o" aria-hidden="true"></i> Hospital</li>        
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
          <h3 class="box-title"><i class="fa fa-hospital-o" aria-hidden="true"></i> Manage Hospital</h3>

          <div class="box-tools pull-right">
            <button data-toggle="modal" data-target="#new-hospital" class="btn btn-danger btn-flat btn-sm "><i class="fa fa-hospital-o" aria-hidden="true"></i> Add Hospital</button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button> -->
          </div>
        </div>
        <div class="box-body">
          <table id="tbl_hospital" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th></th>
                  <th>Hospital Name</th>
                  <th>Address</th>
                  <th>Contact Number</th>                  
                  <th><small>ACTIONS</small></th>
                </tr>
                </thead>
                <tbody>
                <!-- @if(count($hospital_list) > 0)

                  @foreach($hospital_list as $v)
                  <tr>
                    <td><a href="{{ URL::to('hospital/view')}}/{{$v->id}}" title="{{ $v->hospital_name }}">{{ $v->hospital_name }}</a></td>
                    <td>{{ $v->address }}</td>
                    <td>{{ $v->contact_no }}</td>
                    <td style="text-align: center;">
                      
                      <a href="" class="btn btn-success btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                      <a href="" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                    </td>
                  </tr>
                  @endforeach
                  
                @endif -->
                </tbody>
                
              </table>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- Create Hospital Modal -->
<div id="new-hospital" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New Hospital</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ URL::to('/hospital/create')}}">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Hospital Name:</label>
              <input type="text" name="hospital_name" required="" autofocus="" class="form-control">
            </div>
            <div class="form-group">
              <label>Address:</label>
              <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="form-group">
              <label>Contact Number:</label>              
              <input type="text" name="contact_num" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-flat">Save</button>
        </form>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- Edit Hospital Modal -->
<div id="edit-hospital" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Hospital</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ URL::to('/hospital/update')}}">
            {{ csrf_field() }}
            <input type="hidden" name="hopital_id" id="hopital_id">
            <div class="form-group">              
              <label>Hospital Name:</label>
              <input type="text" name="hospital_name" readonly="" id="hospital_name" required="" autofocus="" class="form-control">
            </div>
            <div class="form-group">
              <label>Address:</label>
              <textarea name="address"  id="address" class="form-control" required=""></textarea>
            </div>
            <div class="form-group">
              <label>Contact Number:</label>              
              <input type="number" name="contact_no"  id="contact_num" class="form-control">
            </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-flat">Save</button>
        </form>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
  setTimeout(function(){ $('#alerting_user').hide(200); }, 3000);
  function format ( d ) {
      // `d` is the original data object for the row
      return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
          '<tr>'+
              '<td>Hospital Name:</td>'+
              '<td> &nbsp;'+d.hospital_name+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td>Address :</td>'+
              '<td>'+d.address+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td class="text-info">Contact Number:</td>'+
              '<td>'+d.contact_no+'</td>'+
          '</tr>'+
      '</table>';
  }
  $(document).ready(function(){
    var table = $('#tbl_hospital').DataTable({
      "ajax": "<?php echo URL::to('ajax/getHospitals'); ?>",
      "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "hospital_name" },
            { "data": "address" },
            { "data": "contact_no" },
            { "data": "actions" }
        ],
        "paging": false,
      "scrollY": "200px",
      "scrollCollapse": true,
    });

    $('#tbl_hospital tbody').on('click', 'td.details-control', function () {
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

  function editHospital(id){
    // alert(id);
    
    var settings = {
      "async": true,
      "crossDomain": true,
      "url": "{{ URL::to('ajax/getHospitalInfo') }}/"+id,
      "method": "GET",
      "headers": {
        "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
        "cache-control": "no-cache",
        "postman-token": "1daee8dc-59d1-9cfd-c985-17d3380a82e1"
      }
    }

    $.ajax(settings).done(function (response) {
      var data = JSON.parse(JSON.stringify(response));
      $('#hopital_id').val(data.id);
      $('#hospital_name').val(data.hospital_name);
      $('#address').val(data.address);
      $('#contact_num').val(data.contact_no);

      // hopital_id, , address, contact_num
      $('#edit-hospital').modal('show');
      // console.log(response);
    })
    .fail(function(){
      alert("Oopps, Somehting went wrong.");
    });
    
  }


  function deleteHospital(id){

    var r = confirm("Are you sure that you want delete this Hospital?");
    if(r){
      var settings = {
          "async": true,
          "crossDomain": true,
          "url": "{{ URL::to('hospital/delete') }}/"+id,
          "method": "GET",
          "headers": {
            "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
            "cache-control": "no-cache",
            "postman-token": "c2d9b67f-a2e0-68ae-2a88-a2aa99790aa5"
          }
        }

        $.ajax(settings).done(function (response) {
          var resp = JSON.parse(JSON.stringify(response));          
          location.reload();
        }).fail(function(response){
          alert("Oopps, Something wen't wrong, please try again.");
        });
    }
  }
</script>
@endsection
