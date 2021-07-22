@extends('layouts.app')
@section('add_css_here')
<style type="text/css">
  .control {
    font-family: arial;
    display: block;
    position: relative;
    padding-left: 30px;
    margin-bottom: 15px;
    padding-top: 3px;
    cursor: pointer;
    font-size: 16px;
}
    .control input {
        position: absolute;
        z-index: -1;
        opacity: 0;
    }
.control_indicator {
    position: absolute;
    top: 2px;
    left: 0;
    height: 20px;
    width: 20px;
    background: #e6e6e6;
    border: 0px solid #000000;
}
.control-radio .control_indicator {
    border-radius: undefined%;
}

.control:hover input ~ .control_indicator,
.control input:focus ~ .control_indicator {
    background: #cccccc;
}

.control input:checked ~ .control_indicator {
    background: #2aa1c0;
}
.control:hover input:not([disabled]):checked ~ .control_indicator,
.control input:checked:focus ~ .control_indicator {
    background: #0e6647d;
}
.control input:disabled ~ .control_indicator {
    background: #e6e6e6;
    opacity: 0.6;
    pointer-events: none;
}
.control_indicator:after {
    box-sizing: unset;
    content: '';
    position: absolute;
    display: none;
}
.control input:checked ~ .control_indicator:after {
    display: block;
}
.control-checkbox .control_indicator:after {
    left: 8px;
    top: 4px;
    width: 3px;
    height: 8px;
    border: solid #ffffff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
.control-checkbox input:disabled ~ .control_indicator:after {
    border-color: #7b7b7b;
}
</style>
@endsection
<!-- Main Contents -->
@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daily Reservation Entry
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ URL::to('/dre') }}"><i class="fa fa-dashboard"></i> Daily Reservation Entry</a></li>
        <li class="active">Add New Reservation Entry</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Add New Reservation Entry</h3>

          <div class="box-tools pull-right">            
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <p style="font-style: italic;">Note: Please fill the following information correctly;</p>
          {!! Form::open(array('url' => 'users/createUser')) !!}
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('control_number', 'Control No.:', array('class' => 'text-info')) !!}
              {{ Form::text('control_number', '', ['class'=>'form-control']) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('time_released', 'Time of Released:', array('class' => 'text-info')) !!}
              <input type="time" name="time_released" class="form-control">              
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('availability', 'Availability:', array('class' => 'text-info')) !!}
              {{ Form::select('availability', $arrAvailability, '', ['class'=>'form-control select2']) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('name_of_patient', 'Name of Patient / HD:', array('class' => 'text-info')) !!}
              {{Form::text('name_of_patient', '', ['class'=>'form-control', 'placeholder'=>'e.g. Juan dela Cruz', 'autofocus'=>"", 'required'=>""])}}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('remarks', 'Remarks:', array('class' => 'text-info')) !!}
              <textarea name="remarks" class="form-control"></textarea>
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('hospital', 'Hospital:', array('class' => 'text-info')) !!}
              {{ Form::select('hospital', $hospitals, '', ['class'=>'form-control select2']) }}              
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('blood_type', 'Blood Type:', array('class' => 'text-info')) !!}
              {{ Form::select('blood_type', $arrBloodType, '', ['class'=>'form-control select2']) }}
            </div>
            <!-- UNITS 1 -->
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('no_of_unit1', '# of Unit I:', array('class' => 'text-info')) !!}
              {{ Form::number('no_of_unit1', '', array('class'=>'form-control', 'min'=>0)) }}
            </div>
            <div class="form-group" style="max-width: 40%;">
              {!! Form::label('component1', 'Component I:', array('class' => 'text-info')) !!}
              {{ Form::select('component1', $arrComponent, '', ['class'=>'form-control']) }}
            </div>            
            
            <!-- UNITS 2 -->
            <fieldset style="max-width: 40%;">
              <legend><input type="checkbox" id="unit2"> Component and Unit II</legend>
              <div id="group_component2">
                <div class="form-group">
                  {!! Form::label('no_of_unit2', '# of Unit II:', array('class' => 'text-info')) !!}
                  {{ Form::number('no_of_unit2', '', array('class'=>'form-control', 'min'=>0, 'id'=>'no_of_unit2', 'disabled'=>true)) }}
                </div>
                <div class="form-group">
                  {!! Form::label('component2', 'Component II:', array('class' => 'text-info')) !!}
                  {{ Form::select('component2', $arrComponent, '', ['class'=>'form-control', 'id'=>'component2', 'disabled'=>true]) }}
                </div>
              </div>
            </fieldset>

            <!-- UNITS 3 -->
            <fieldset style="max-width: 40%;">
              <legend><input type="checkbox" class="minimal-red"  id="unit3"> Component and Unit III</legend>
              <div id="group_component3">                
                <div class="form-group">
                  {!! Form::label('no_of_unit3', '# of Unit III:', array('class' => 'text-info')) !!}
                  {{ Form::number('no_of_unit3', '', array('class'=>'form-control', 'min'=>0, 'id'=>'no_of_unit3', 'disabled'=>true)) }}
                </div>
                <div class="form-group">
                  {!! Form::label('component3', 'Component III:', array('class' => 'text-info')) !!}
                  {{ Form::select('component3', $arrComponent, '', ['class'=>'form-control','id'=>'component3', 'disabled'=>true]) }}
                </div>
              </div>
            </fieldset>
            <div class="form-group">
              {!!Form::submit('Reserve', ['class'=> 'btn btn-danger btn-flat'])!!}
            </div>
          {!! Form::close() !!}
          </form>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection

@section('custom_js')
<!-- iCheck 1.0.1 -->
<script src="{{ URL::asset('bower_components\AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
        $('#group_component2').hide();
        $('#group_component3').hide();
        $('#unit2').click(function(){
          var ischecked= $('#unit2').is(':checked');
          // alert("Checked: "+ischecked);
          if(ischecked){
            $('#no_of_unit2').prop("disabled", false);
            $('#component2').prop("disabled", false);
            $('#group_component2').show(100);
            // alert('checked');
            // $('no_of_unit2').removeAttr('disabled');
            // no_of_unit2
            // component2
          }else{
            // alert('Unchecked');
            $('#no_of_unit2').prop("disabled", true);
            $('#component2').prop("disabled", true);
            $('#group_component2').hide(100);
          }
        });

        $('#unit3').click(function(){

          var ischecked= $('#unit3').is(':checked');
          // alert("Checked: "+ischecked);
          if(ischecked){
            $('#no_of_unit3').prop("disabled", false);
            $('#component3').prop("disabled", false);
            $('#group_component3').show(100);
            // alert('checked');
            // $('no_of_unit2').removeAttr('disabled');
            // no_of_unit2
            // component2
          }else{
            // alert('Unchecked');
            $('#no_of_unit3').prop("disabled", true);
            $('#component3').prop("disabled", true);
            $('#group_component3').hide(100);
          }
        });

        function showHideComponentsII(){
          alert("TEST");
          var ischecked= $('#unit2').is(':checked');
          // alert("Checked: "+ischecked);
          if(ischecked){
            $('#no_of_unit2').prop("disabled", false);
            $('#component2').prop("disabled", false);
            $('#group_component2').show(100);
            // alert('checked');
            // $('no_of_unit2').removeAttr('disabled');
            // no_of_unit2
            // component2
          }else{
            // alert('Unchecked');
            $('#no_of_unit2').prop("disabled", true);
            $('#component2').prop("disabled", true);
            $('#group_component2').hide(100);
          }
        }
    });
    
</script>
@endsection