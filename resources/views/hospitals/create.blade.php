@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Hospital</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/hospital') }}"><i class="fa fa-hospital-o"></i> Hospital</a></li>
        <li class="active"><i class="fa fa-hospital-o" aria-hidden="true"></i> Add Hospital</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-hospital-o"></i> Add Hospital</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form method="POST" action="{{ URL::to('/hospital/create')}}">
            {{ csrf_field() }}
            <div class="form-group" style="max-width: 500px;">
              <label>Hospital Name:</label>
              <input type="text" name="hospital_name" required="" autofocus="" class="form-control">
            </div>
            <div class="form-group" style="max-width: 500px;">
              <label>Address:</label>
              <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="form-group" style="max-width: 500px;">
              <label>Contact Number:</label>              
              <input type="text" name="contact_num" class="form-control">
            </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-danger btn-flat">Save</button>
          </form>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
