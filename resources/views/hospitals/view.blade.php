@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Hospital</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/hospital') }}"><i class="fa fa-hospital-o"></i> Hospital</a></li>
        <li class="active"><i class="fa fa-hospital-o" aria-hidden="true"></i> Views Hospital</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-hospital-o"></i> View Hospital</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table class="table">
            <tr >
              <td style="width: 11%;">Hospital Name:</td>
              <td>{{ $hospital->hospital_name }}</td>
              <td></td>
            </tr>
            <tr >
              <td style="width: 11%;">Address:</td>
              <td>{{ $hospital->address }}</td>
              <td></td>
            </tr>
            <tr >
              <td style="width: 11%;">Contact Number:</td>
              <td>{{ $hospital->contact_no }}</td>
              <td></td>
            </tr>
          </table>
          <a href="{{ URL::to('/hospital') }}" class="btn btn-danger btn-flat">Back</a>
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
