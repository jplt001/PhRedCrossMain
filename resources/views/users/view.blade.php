@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
        <small> {{ $user_inf->name }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/users') }}"><i class="fa fa-users"></i> Users</a></li>
        <li class="active"><i class="fa fa-user" aria-hidden="true"></i> View User</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ $user_inf->name }}</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table class="table">
            <thead>
              <tr>
                <th colspan="2">Info</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>ID: </td>
                <td>{{ $user_inf->id }}</td>              
              </tr>
              <tr>
                <td>Name: </td>
                <td>{{ $user_inf->name }}</td>              
              </tr>
              <tr>
                <td>Email: </td>
                <td>{{ $user_inf->email }}</td>              
              </tr>
              <tr>
                <td>User Type: </td>
                <td>{{ $user_inf->user_type }}</td>              
              </tr>
              <tr>
                <td>Member Since: </td>
                <td>{{ date('F d, Y', strtotime($user_inf->created_at)) }}</td>              
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
