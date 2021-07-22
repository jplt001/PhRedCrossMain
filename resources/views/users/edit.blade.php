@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit
        <small> User</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/users') }}"><i class="fa fa-users"></i> Users</a></li>
        <li class="active"><i class="fa fa-user-plus" aria-hidden="true"></i> Create User</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Users</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">

          {!! Form::open(array('url' => 'users/update')) !!}
            <input type="hidden" name="emp_id" value="{{$users_inf->id}}">
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('branch', 'Branch:', array('class' => 'text-info')) !!}

              @if($user_info->user_type != 1)
                {{ Form::select('branch', $branch, $users_inf->branch_id, ['class'=>'form-control', 'disabled' => true]) }}
              @else
                {{ Form::select('branch', $branch, $users_inf->branch_id, ['class'=>'form-control']) }}
              @endif
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('name', 'Name:', array('class' => 'text-info')) !!}
              {{Form::text('name', $users_inf->name, ['class'=>'form-control', 'placeholder'=>'e.g. Juan dela Cruz', 'autofocus'=>"", 'required'=>""])}}
              <!-- <input type="text" name="name" required="" autofocus="" class="form-control"> -->
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('email', 'Email:', array('class' => 'text-info')) !!}
              {{Form::email('email', $users_inf->email, ['class'=>'form-control', 'placeholder'=>'e.g. juandelacruz@gmail.com', 'autofocus'=>"", 'required'=>""])}}
              <!-- <input type="text" name="name" required="" autofocus="" class="form-control"> -->
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('user_type', 'User Type:', array('class' => 'text-info')) !!}
              {{ Form::select('user_type', $user_type, $users_inf->user_type, ['class'=>'form-control']) }}
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('password', 'Password:', array('class' => 'text-info')) !!}
              {{ Form::password('password', ['class'=>'form-control']) }}
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('password', 'Re-type Password:', array('class' => 'text-info')) !!}
              {{ Form::password('password', ['class'=>'form-control', 'onkeyup'=>'console.log("TEST")']) }}
            </div>
            <div class="form-group" style="max-width: 500px;">
              {!! Form::label('gender', 'Gender:', array('class' => 'text-info')) !!}
              {{ Form::select('gender', [0=>'Male', 1=>'Female'], $users_inf->gender, ['class'=>'form-control']) }}
            </div>
            
            <!-- <div class="form-group" style="max-width: 500px;">
              {!! Form::label('user_access', 'User Access: ', ['class'=>'text-info']) !!}
              {{ Form::select('gender', $user_access_type, $users_access, ['class'=>'form-control', 'multiple']) }}
            </div> -->
            
                    
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="form-group">
              {!!Form::submit('Update User', ['class'=> 'btn btn-danger btn-flat'])!!}
            </div>
          {!! Form::close() !!}
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
