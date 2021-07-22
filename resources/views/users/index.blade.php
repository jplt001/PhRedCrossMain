@extends('layouts.app')
@section('add_css_here')
<style type="text/css">
  #example1{
    cursor: pointer;
  }
  .dataTables_info{
    color: #31708f;
  }
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users
      <small> View and manage users</small>
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-users" aria-hidden="true"></i> Users</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-7 col-sm-7">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users" aria-hidden="true"></i> Users</h3>
              <div class="box-tools pull-right">
                <a href="{{ URL::to('/users/create')}}" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-user-plus" aria-hidden="true"></i> Create User</a>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button> -->            
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>                 
                  <th><small>ACTIONS</small></th>
                </tr>
                </thead>
                <tbody>
                @foreach($list_users as $v)
                <tr>
                <!-- <a href="{{ URL::to('/users/view') }}/{{$v->id}}">  </a> -->
                  <td>{{ $v->id}}</td>
                  <td>{{ $v->name}} </td>
                  <td>{{ $v->email}}</td>                  
                  <td style="text-align: center;">
                      <a href="{{URL::to('users/update') }}/{{ $v->id }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>                      
                      @if($v->id != $user_info->id)
                        <a href="{{URL::to('users/delete')}}/{{ $v->id }}" class="btn btn-danger btn-xs "><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                      @else
                        <a class="btn btn-danger btn-xs " disabled=""><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                      @endif
                  </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th><small>ACTIONS</small></th>
                </tr>
                </tfoot>
              </table>
            </div>
            <div class="box-footer">
               
            </div>
          </div>
        </div>
        <div class="col-md-5 col-sm-5">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">User Information</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <table class="table">              
                <tr>
                  <td>Branch: </td>
                  <td class="text-info" id="branch"> N/A </td>
                </tr>
                <tr>
                  <td>Name: </td>
                  <td  class="text-info" id="name"> N/A </td>
                </tr>
                <tr>
                  <td>Email: </td>
                  <td  class="text-info" id="email"> N/A </td>
                </tr>
                <tr>
                  <td>Gender: </td>
                  <td  class="text-info" id="gender"> N/A </td>
                </tr>
                <tr>
                  <td>Account Type: </td>
                  <td  class="text-info" id="account_type"> N/A </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
  var table = $('#example1').DataTable({
      "paging": false,
      "scrollY": "800px",
      "scrollCollapse": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollX": true
    });
    $('#example1 tbody').on('click', 'tr', function () {
        
        var data = table.row(this).data();
        var id = data[0];
        $.get( "<?php echo URL::to('/ajax/getUserInfo')  ?>/"+id, function( data ) {
          $('#name').text(data.name);
          $('#email').text(data.email);
          $('#branch').text(data.branch_name);
          if(data.gender == 1){
            $('#gender').text("Female");
          }else if(data.gender == 0){
            $('#gender').text("Male");
          }
          if(data.user_type == 0){            
            $('#account_type').text("Super Administrator");
          }else if(data.user_type == 1){
            $('#account_type').text("Administrator");
          }else{
            $('#account_type').text("Employee");
          }

        });


    });
</script>
@endsection
