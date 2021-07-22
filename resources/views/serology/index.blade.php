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
        Serology
        <small>View and manage Serology </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-wheelchair" aria-hidden="true"></i> Serology</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#donor-pane" data-toggle="tab">Serology Laboratory Results</a></li>
              <li><a href="#lab-pane" data-toggle="tab">Laboratory Results <small class="text-green">Final Results - Repeated Results</small></a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="donor-pane">
                <div class="form-group" style="text-align: right;">
                  <?php $res =  acl_roles( Auth::user()->user_type, 'serology', 'create' ); ?>
                  @if( $res )
                    <button type="button" class="btn btn-danger btn-flat btn-xs" data-toggle="modal" data-target="#new-donor-results-modal"><i class="fa fa-wheelchair" aria-hidden="true"></i> Add Donor Results</button>
                  @endif
                </div>
                <table class="table" id="tbl-donor-results">
                  <thead>
                   <tr>
                      <th style="min-width: 150px">Name of MBD</th>
                      <th style="min-width: 150px">Chapter Branch</th>
                      <th style="min-width: 100px">Serial No.</th>
                      <th style="min-width: 100px">Final Blood Type</th>
                      <th style="min-width: 20px">Anti-HIV</th>
                      <th style="min-width: 20px">HBSAG</th>
                      <th style="min-width: 20px">ANTI-HCV</th>
                      <th style="min-width: 20px">SYPHILIS</th>
                      <th style="min-width: 20px">MALARIA</th>
                      <th style="min-width: 100px">Status</th>
                      <th style="min-width: 100px">Date Released</th>
                      <th style="min-width: 10px">&nbsp;</th>
                      <th style="min-width: 10px">&nbsp;</th>
                      <th>&nbsp;</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="lab-pane">
                <table class="table" id="tbl-lab-results">
                  <thead>
                   <tr>
                      <th style="min-width: 100px">Serial No.</th>
                      <th style="min-width: 150px">Extraction Date</th>
                      <th style="min-width: 100px">Source</th>
                      <th style="min-width: 100px">Name of Organization</th>
                      <th style="min-width: 100px">Branch / Chapter</th>
                      <th style="min-width: 100px">Blood Type</th>
                      <th style="min-width: 100px">Rh</th>
                      <th style="min-width: 100px">Orig Lab No.</th>
                      <th style="min-width: 100px">Sample Taken From</th>
                      <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
    <!-- Modal -->    
  <div id="new-donor-results-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">New Donor Result</h4>
        </div>
        <div class="modal-body" style="height: 490px; overflow: auto;">
          <form id="new-donor-form" method="post" action="<?php echo URL::to('serology/save') ?>">
            {{ csrf_field() }}
            <div class="form-group">              
              <label>Serial Number:</label>
              <input type="text" name="serial_no" class="form-control" required="" >
            </div>
            <div class="form-group">
              <label for="extraction_date">Extraction Date:</label>
              <input type="text" name="extraction_date" id="extraction_date" class="form-control" style="text-align: center;" required="">
            </div>
            <div class="form-group">              
              <label>Donor: </label>
              <select class="form-control select2" name="patient_id" style="width: 100%;">
                @foreach($blood_donors as $v)
                  
                    @if($v->is_individual == 0)                    
                      <option value="{{ $v->id }}" class="text-red">{{ $v->last_name }}, {{ $v->first_name }} - 
                        <b class="text-info">Individual</b>
                      </option>
                    @else
                    <option value="{{ $v->id }}" class="text-aqua">{{ $v->last_name }}, {{ $v->first_name }} - 
                    <b>Group / Organization</b>
                    </option>
                    @endif
                  
                @endforeach
              </select>
            </div>
            
            <div class="form-group">
                  <label>Initial Blood Type:</label>
                  <select class="form-control select2"  name="blood_type" style="width: 100%;">
                    @foreach($blood_types as $v)
                    <option value="{{ $v->id }}"> {{$v->name}}</option>
                    @endforeach
                  </select>  
                
            </div>
            <div class="form-group">
              <label for="branch">Branch/ Chapter</label>
              <select class="form-control select2" name="branch_id" style="width: 100%;">
                  @foreach($branches as $v)
                    <option value="{{ $v->id }}"> {{$v->branch_name}}</option>
                  @endforeach
              </select>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-md-4">
                  <label>ANTI-HIV</label>
                  <input type="number" name="anti_hiv" min="0" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                  <label>HBSAG</label>
                  <input type="number" name="hbsag" min="0" class="form-control" value="0">
                </div>
                <div class="col-md-4">
                  <label>Anti-HCV</label>
                  <input type="number" name="anti_hcv" min="0" class="form-control" value="0">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label>Syphilis</label>
                  <input type="number" name="syphilis" class="form-control" value="0">
                </div>
                <div class="col-md-6">
                  <label>Malaria</label>
                  <input type="number" name="malaria" class="form-control" value="0">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-5">
                <label>Component</label>
                {{ Form::select('component[0].component', $component, '', array('class'=>'form-control', 'id'=>'patient_type', 'readonly'=>'true')) }}
              </div>
              <div class="col-xs-5">
                <label>Quantity</label>
                {{ Form::number('component[0].qty', 0 , array('class'=>'form-control', 'id'=>'patient_type', 'readonly'=>'true')) }}
              </div>
              <div class="col-xs-2">
                <button type="button" class="btn btn-default addButton" id="addButton" style="margin-top: 1.7em;"><i class="fa fa-plus"></i></button>
              </div>
            </div>

            <!-- The template for adding new field -->
            <div class="form-group hide" id="bookTemplate" style="padding-top: 3em">
                <div class="col-xs-5">
                {{ Form::select('component', $component, '', array('class'=>'form-control', 'id'=>'patient_type')) }}
              </div>
              <div class="col-xs-5">
                {{ Form::number('qty', 0 , array('class'=>'form-control', 'id'=>'patient_type')) }}
              </div>
                <div class="col-xs-2">
                    <button type="button" class="btn btn-default" id="removeButton"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- <div class="form-group">
              <label>Result:</label>
              <select class="form-control"  name="f_blood_type">
                <option value="0">Passed</option>
                <option value="1">Failed</option>
              </select>
            </div> -->
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Save</button>
          </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="edit-serology1" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Test Result</h4>
        </div>
        <div class="modal-body"  style="height: 490px; overflow: auto;">
          <form method="post" action="<?php echo URL::to('serology/updateSerologyLabResults') ?>">
            {{ csrf_field() }}
            <input type="hidden" name="patient_id" id="patient_id">
            <fieldset>
              <legend>Bag Info</legend>
              <div class="form-group">
                <label>Bag Used</label>
                <select class="form-control select2" name="bag_used" style="width: 100%;">
                  <option value="BQ">BQ</option>
                  <option value="TQ">TQ</option>
                  <option value="FQ">FQ</option>
                  <option value="FT">FT</option>
                  <option value="TT">TT</option>
                  <option value="TD">TD</option>
                  <option value="FQ-TB">FQ-TB</option>
                  <option value="TS450">TS450</option>
                </select>
              </div>
              <div class="form-group">
                <label>Bag Condition</label>
                <select class="form-control select2" name="bag_condition" style="width: 100%;">
                  <option value="GOOD">GOOD</option>
                  <option value="SPOILED">SPOILED</option>
                </select>
              </div>
            </fieldset>
            <fieldset>
              <legend>Blood Type</legend>
              <div class="form-group">
                <label>Final Blood Type</label>
                <select class="form-control" name="final_blood_type">
                    @foreach($blood_types as $v)
                      <option value="{{ $v->id }}"> {{$v->name}}</option>
                    @endforeach
                </select>
              </div>
            </fieldset>
            <fieldset>
              <legend>Tests Result</legend>
              <!-- ANTI-HIV TEST -->
              <div class="form-group">
                <label>ANTI-HIV MTD</label>
                {{ Form::select('anti_hiv_mtd', $arr_anti_hiv_mtd, '', ['class'=>'form-control']) }}
              </div>
            
              <div class="form-group">
                <label>ANTI-HIV RESULT</label>
                {{ Form::select('anti_hiv_result', $arr_test_res, '', ['class'=>'form-control']) }}
              </div>
              <!-- HBSAG TEST -->
              <div class="form-group">
                <label>HBSAG MTD</label>
                {{ Form::select('hbsag_mtd', $arr_anti_hiv_mtd, '', ['class'=>'form-control']) }}
              </div>
            
              <div class="form-group">
                <label>HBSAG RESULT</label>
                {{ Form::select('hbsag_result', $arr_test_res, '', ['class'=>'form-control']) }}
              </div>

              <!-- ANTI-HCV TEST -->
              <div class="form-group">
                <label>ANTI-HCV MTD</label>
                {{ Form::select('anti_hcv_mtd', $arr_anti_hiv_mtd, '', ['class'=>'form-control']) }}
              </div>
            
              <div class="form-group">
                <label>ANTI-HCV RESULT</label>
                {{ Form::select('anti_hcv_result', $arr_test_res, '', ['class'=>'form-control']) }}
              </div>

              <!-- SYPHILIS TEST -->
              <div class="form-group">
                <label>SYPHILIS MTD</label>
                {{ Form::select('syphilis_mtd', $arr_anti_hiv_mtd, '', ['class'=>'form-control']) }}
              </div>
            
              <div class="form-group">
                <label>SYPHILIS RESULT</label>
                {{ Form::select('syphilis_result', $arr_test_res, '', ['class'=>'form-control']) }}
              </div>

              <!-- MALARIA TEST -->
              <div class="form-group">
                <label>MALARIA MTD</label>
                {{ Form::select('malaria_mtd', $arr_anti_hiv_mtd, '', ['class'=>'form-control']) }}
              </div>
            
              <div class="form-group">
                <label>MALARIA RESULT</label>
                {{ Form::select('malaria_result', $arr_test_res, '', ['class'=>'form-control']) }}
              </div>
            </fieldset>
            <fieldset>
              <legend>Initial Remarks Value</legend>
              <div class="form-group">
                <label>HIV</label>
                <input type="number" step="0.001" min="0.000" value="0.000" name="hiv" class="form-control" required="">
              </div>
              <div class="form-group">
                <label>HBS</label>
                <input type="number" step="0.001" min="0.000" value="0.000" name="hbs" class="form-control" required="">
              </div>
              <div class="form-group">
                <label>HCV</label>
                <input type="number" step="0.001" min="0.000" value="0.000" name="hcv" class="form-control" required="">
              </div>
              <div class="form-group">
                <label>SYP</label>
                <input type="number" step="0.001" min="0.000" value="0.000" name="syp" class="form-control" required="">
              </div>
              <div class="form-group">
                <label>MAL</label>
                <input type="number" step="0.001" min="0.000" value="0.000" name="mal" class="form-control" required="">
              </div>
            </fieldset>
            <div class="form-group">
              <label>Date Released: </label>
              <input type="text" name="date_released" id="date_released" class="form-control" style="text-align: center;" required="">
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Save</button>
          </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="edit-serology2" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Test Result</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="<?php echo URL::to('serology/updateFinalLabResults') ?>">
            {{ csrf_field() }}
            <input type="hidden" name="patient_id2" id="patient_id2">
            <div class="form-group">
              <label>Source:</label>
              <select class="form-control select2" name="source" style="width: 100%;">
                <option value="MBD">MBD</option>
                <option value="MCL">MCL</option>
                <option value="TEST RUN">TEST RUN</option>
                <option value="EQAS">EQAS</option>
                <option value="IQAS">IQAS</option>
                <option value="Walk-In">Walk-In</option>
                <option value="" selected=""></option>
              </select>
            </div>
            <div class="form-group">
              <label>Orig Lab No.</label>
              <input type="text" name="orig_lab_no" class="form-control" required="">
            </div>
            <div class="form-group">
              <label>Sample Taken From</label>
              <select class="form-control" name="sample_taken_from">
                <option value="ORIG TUBE">ORIG TUBE</option>
                <option value="BAG">BAG</option>
                <option value="RTND BLD">RTND BLD</option>
                <option value="" selected=""></option>
              </select>
            </div>          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Save</button>
          </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('custom_js')
<script type="text/javascript">
  $(document).ready(function(){
     var componentValidators = {
            row: '.col-xs-5',   // The title is placed inside a <div class="col-xs-4"> element
            validators: {
                notEmpty: {
                    message: 'The component is required'
                }
            }
        },
        quantityValidators = {
            row: '.col-xs-2',
            validators: {
                notEmpty: {
                    message: 'The Quantity is required'
                },
                numeric: {
                    message: 'The quantity must be a numeric number'
                }
            }
        },
        bookIndex = 0;
    // var table = $('#tbl-donor-results').DataTable({
    //   "scrollX": true,
    //   "aaSorting": []
    //   // "columnDefs": [ 
    //   //   { "targets": [11, 12], "orderable": false }        
    //   // ]

    // });
    // var table2 = $('#tbl_lab_results').DataTable({
    //   "scrollX": true,
    //   "aaSorting": []
    //   // "columnDefs": [ 
    //   //   { "targets": [11, 12], "orderable": false }        
    //   // ]

    // });

    $('#extraction_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: '+0d',
      });

    $('#date_released').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        endDate: '+0d'
      });

     $('#addButton').on('click', function() {
      
            bookIndex++;
            var $template = $('#bookTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', bookIndex)
                                .attr('id', "component"+bookIndex)
                                .insertBefore($template);

                                // .attr('onclick', "removeMeme('component"+bookIndex+"')")

            // Update the name attributes
            $clone
                .find('[name="component"]').attr('name', 'components[' + bookIndex + '].component').end()
                .find('[id="removeButton"]').attr('onclick', "removeMeme('component"+bookIndex+"')").end()
                .find('[name="qty"]').attr('name', 'components[' + bookIndex + '].qty').end();

            
            // $("#component"+bookIndex+" removeButton").attr('onclick', "removeMeme('component"+bookIndex+"')")
            // Add new fields
            // Note that we also pass the validator rules for new field as the third parameter
            // $('#new-donor-form')
            //     .formValidation('addField', 'components[' + bookIndex + '].component', componentValidators)
                // .formValidation('addField', 'components[' + bookIndex + '].qty', quantityValidators);
        })
     // Remove button click handler
       $('#new-donor-form').on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group'),
                index = $row.attr('data-book-index');

            // Remove fields
            // $('#bookForm')
            //     .formValidation('removeField', $row.find('[name="book[' + index + '].title"]'))
            //     .formValidation('removeField', $row.find('[name="book[' + index + '].isbn"]'))
            //     .formValidation('removeField', $row.find('[name="book[' + index + '].price"]'));

            // Remove element containing the fields
            $row.remove();
        });
     
  });

  function removeMeme(id){

    $('#'+id).remove();

  }


</script>
@endsection

