@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Patient
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/patient') }}"><i class="fa fa-wheelchair"></i> Patient</a></li>
        <li class="active"><i class="fa fa-wheelchair" aria-hidden="true"></i> View Patient Information</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">View Patient Information</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
          <div class="form-group">
            <label class="text-info"> First Name: </label>
            <span>{{$patient_info->first_name}}</span>
          </div>
          <div class="form-group">
            <label class="text-info"> Middle Name: </label>
            <span>{{$patient_info->middle_name}}</span>
          </div>
          <div class="form-group">
            <label class="text-info"> Last Name: </label>
            <span>{{$patient_info->last_name}}</span>
          </div>
          <div class="form-group">
            <label class="text-info"> Address: </label>
            <span>{{$patient_info->address}}</span>
          </div>
          <div class="form-group">
            <label class="text-info"> Contact Number: </label>
            <span>{{$patient_info->contact_no}}</span>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="{{ URL::asset('/patient') }}" class="btn btn-danger">Back</a>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
