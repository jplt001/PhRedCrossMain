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
        <li class="active"><i class="fa fa-wheelchair" aria-hidden="true"></i> Add New Patient</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Add New Patient</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          {!! Form::open(['url' => 'patient/create']) !!}
            <div class="form-group" style="max-width: 40%;">
              {{ Form::label('first_name', 'First Name:', ['class' => 'text-info']) }}
              {{ Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. Juan', 'required'=>true]) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {{ Form::label('middle_name', 'Middle Name:', ['class' => 'text-info']) }}
              {{ Form::text('middle_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. Jimenez', 'required'=>true]) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {{ Form::label('last_name', 'Last Name:', ['class' => 'text-info']) }}
              {{ Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>' e.g. De La Cruz', 'required'=>true]) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {{ Form::label('address', 'Address:', ['class' => 'text-info', 'required'=>true]) }}
              <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="form-group" style="max-width: 40%;">
              {{ Form::label('contact_no', 'Contact Number:', ['class' => 'text-info']) }}
              {{ Form::text('contact_no', null, ['class'=>'form-control', 'placeholder'=>' e.g. 09123456789', 'max'=>12, 'required'=>true]) }}
            </div>
            <input type="hidden" name="added_by" value="{{$user_info->id}}">
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

          {{ Form::submit('Save' , ['class'=>'btn btn-danger']) }}
          {!! Form::close() !!}
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
