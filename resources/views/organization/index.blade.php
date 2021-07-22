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
        Organization
        <small> View and manage organization</small>        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-hospital-o" aria-hidden="true"></i> Organization</li>        
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
          <h3 class="box-title"><i class="fa fa-hospital-o" aria-hidden="true"></i> Manage Organization</h3>

          <div class="box-tools pull-right">
            <button data-toggle="modal" data-target="#new-organization" class="btn btn-danger btn-flat btn-sm "><i class="fa fa-hospital-o" aria-hidden="true"></i> Add Organization</button>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button> -->
          </div>
        </div>
        <div class="box-body">
          <table id="tbl_org" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th></th>
                  <th>Organization Name</th>
                  <th>Address</th>
                  <th>Created When</th>                  
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
      <div class="modal fade" id="edit-organization">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"> Edit Organization</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL::to('organization/update') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="org_id" id="org_id">
                        <div class="form-group">
                          <label>Organization Name:</label>
                          <input type="text" name="organization_name" class="form-control" id="organization_name">
                        </div>
                         <div class="form-group" style="resize: vertical;">
                            <label>Address:</label>
                            <textarea name="address" id="address" class="form-control"></textarea>
                          </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-modal-reservation-save" data-title="">Save</button>
                </form>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
      <div class="modal fade" id="new-organization">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"> New Organization</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL::to('organization/create') }}">
                        {{ csrf_field() }}                        
                        <div class="form-group">
                          <label>Organization Name:</label>
                          <input type="text" name="organization_name" class="form-control" id="organization_name">
                        </div>
                         <div class="form-group" style="resize: vertical;">
                            <label>Address:</label>
                            <textarea name="address" id="address" class="form-control"></textarea>
                          </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-modal-reservation-save" data-title="">Save</button>
                </form>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
  setTimeout(function(){ $('#alerting_user').hide(200); }, 3000);
  function format ( d ) {
      // `d` is the original data object for the row
      return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
          '<tr>'+
              '<td class="text-info">Organization Name:</td>'+
              '<td> &nbsp;'+d.organization_name+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td class="text-info">Address :</td>'+
              '<td>'+d.address+'</td>'+
          '</tr>'+
          '<tr>'+
              '<td class="text-info">Created When:</td>'+
              '<td>'+d.added_when+'</td>'+
          '</tr>'+
      '</table>';
  }
  $(document).ready(function(){
    var table = $('#tbl_org').DataTable({
      "ajax": "<?php echo URL::to('ajax/getOrganizations'); ?>",
      "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "organization_name" },
            { "data": "address" },
            { "data": "added_when" },
            { "data": "actions" }
        ],
        "paging": false,
      "scrollY": "200px",
      "scrollCollapse": true,
    });

    $('#tbl_org tbody').on('click', 'td.details-control', function () {
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


  function editOrganization(id){
    var settings = {
      "async": true,
      "crossDomain": true,
      "url": "{{ URL::to('ajax/getOrgInfo')}}/"+id,
      "method": "GET",
      "headers": {
        "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
        "cache-control": "no-cache",
        "postman-token": "7d4157b6-f481-d4e8-63cd-ae524c7f492c"
      }
    }

    $.ajax(settings).done(function (response) {
      $('#edit-organization').modal('show');
      var data = JSON.parse(JSON.stringify(response));
      
      $("#org_id").val(data.id);
      $("#organization_name").val(data.organization_name);
      $('#address').val(data.address);
    });
    
  }


  function deleteOrganization(id){

    var r = confirm("Are you sure that you want delete this Organization ?");
    if(r){
      var settings = {
        "async": true,
        "crossDomain": true,
        "url": "{{ URL::to('organization/setDelete')}}/"+id,
        "method": "GET",
        "headers": {
          "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
          "cache-control": "no-cache",
          "postman-token": "93d0caa2-5f52-3fed-61f0-36f01e64b136"
        }
      }

      $.ajax(settings).done(function (response) {
        location.reload();
      }).failed(function(){
        alert("Oops, Something  wen't wrong.");
      });
      
    }
  }

</script>
@endsection
