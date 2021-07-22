@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Blood Donor
            <small> View and manage blood register</small>
        </h1>
        <div class="section-tools text-right">
            <a href="#"><i class="fa fa-question-circle"></i> Help</a>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(\Session::has('success'))
            <div class="callout callout-success" id="alerting_user">
               {{ \Session::get('success')}}
            </div>
          @endif
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Today's Donors</a></li>
                      <li><a href="#tab_2" data-toggle="tab">Blood Donors</a></li>
                      <!-- <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li> -->                    
                      <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">
                        <div class="form-group" style="text-align: right;">
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-new-old"><i class="fa fa-plus"></i> New</button>
                        </div>
                        <table class="table" id="today-donor-table">
                            <thead>
                                <th>New/Old</th>
                                <th>Donor Name</th>                                
                                <th>Source</th>
                                <th>Category</th>
                                
                                <th>Donor No. per MBD</th>                                
                                <th>Remarks</th> 

                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($today_blood_donors as $v)
                               <tr>
                                    <td>
                                      @if($v->is_new == 1)
                                        <span class="label label-success">NEW</span>
                                        @else
                                            <span class="label label-info">OLD</span>
                                        @endif
                                   </td>
                                   <td>{{ $v->donor_name }}</td>
                                   <td>{{ $v->source }}</td>
                                   <td>{{ $v->category }}</td>
                                   <td>{{ $v->donor_no_per_mbd }}</td>
                                   <td>{{ $v->remarks }}</td>
                                   
                                   
                                   <td>
                                        @if($v->is_passed == 0)
                                        <span class="text-info">Pending</span>
                                        @elseif($v->is_passed == 1)
                                        <span class="text-green">Accepted</span>
                                        
                                        @else
                                        <span class="text-red">Deffered</span>
                                        
                                        @endif
                                   </td>
                                   <td>
                                        @if($v->is_passed == 0)
                                    <button onclick="editDailyDonor({{ $v->id }})" class="btn btn-info btn-xs btn-flat" data-toggle="tooltip" data-title="Edit"><i class=" fa fa-pencil"></i></button></td>
                                   <td><a href="{{ URL::to('blood_register/setApproved') }}/{{$v->id}}" data-toggle="tooltip" data-title="Accept" class="btn btn-success btn-xs btn-flat" ><i class="fa fa-check"></i></a>
                                    <button onclick="preSetDeffered({{ $v->id}})" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-ban"></i></button>
                                @else
                                <button onclick="editDailyDonor({{ $v->id }})" class="btn btn-info btn-xs btn-flat" data-toggle="tooltip" data-title="Edit"><i class=" fa fa-pencil"></i></button></td>
                                   <td><a disabled="" href="" data-toggle="tooltip" data-title="Accept" class="btn btn-success btn-xs btn-flat" ><i class="fa fa-check"></i></a>
                                    <button disabled="" onclick="preSetDeffered({{ $v->id}})" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-ban"></i></button>
                                @endif
                            </td>
                               </tr>
                                @endforeach
                            </tbody>
                        </table>    
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2">
                        <div class="form-group" style="text-align: right;">
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-new-donor"><i class="fa fa-plus"></i> Add Blood  Donor</button>
                        </div>
                        <table class="table" id="donor-table">
                            <thead>
                                <th>Donor Name</th>
                                <th>Birthday</th>
                                <th>Civil Status</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Lot</th>
                                <th>Street</th>
                                <th>Town/Municipality</th>
                                <th>Province/City</th>
                                <th>ZIP Code</th>
                                <th>Office Address</th>
                                <th>Nationality</th>
                                <th>Religion</th>
                                <th>Education</th>
                                <th>Occupation</th>
                                <th>Telephone Number</th>
                                <th>Cellphone Number</th>
                                <th>Email Address</th>
                                <th>New/Old</th>
                                <th>ACTIONS</th>
                            </thead>
                            <tbody>
                                @foreach($blood_donors as $v)
                                <tr>
                                    <td class="text-info">{{ $v->last_name }}, {{ $v->first_name }}</td>
                                    <td>{{ $v->birth_date }}</td>
                                    <td>{{ $v->civil_status }}</td>
                                    <td>{{ $v->age }}</td>
                                    <td>{{ $v->gender }}</td>
                                    <td>{{ $v->lot_no }}</td>
                                    <td>{{ $v->street }}</td>
                                    <td>{{ $v->barangay }}</td>
                                    <td>{{ $v->town_municipality }}</td>
                                    <td>{{ $v->province_city }}</td>
                                    <td>{{ $v->zip_code }}</td>
                                    <td>{{ $v->office_address }}</td>
                                    <td>{{ $v->nationality }}</td>
                                    <td>{{ $v->religion }}</td>
                                    <td>{{ $v->education }}</td>
                                    <td>{{ $v->occupation }}</td>
                                    <td>{{ $v->tel_no }}</td>
                                    <td>{{ $v->cell_no }}</td>
                                    <td style="text-align: center;">
                                        @if($v->is_new == 1)
                                        <span class="label label-success">NEW</span>
                                        @else
                                            <span class="label label-info">Old</span>
                                        @endif                                        
                                    </td>
                                    <td>
                                        <!-- <a href="" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i></a>  -->
                                        <button class="btn btn-info btn-flat btn-xs" onclick="editBloodDonor('{{$v->id}}')"><i class="fa fa-pencil"></i></button> 
                                        <button class="btn btn-danger btn-flat btn-xs" onclick="deletesPatient({{$v->id}})"><i class="fa fa-trash"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                      
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
                <!-- <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Blood Donor</h3>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-new-donor"><i class="fa fa-plus"></i> Add Blood  Donor</button>                            
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table" id="donor-table">
                            <thead>
                                <th>Donor Name</th>
                                <th>Birthday</th>
                                <th>Civil Status</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Lot</th>
                                <th>Street</th>
                                <th>Town/Municipality</th>
                                <th>Province/City</th>
                                <th>ZIP Code</th>
                                <th>Office Address</th>
                                <th>Nationality</th>
                                <th>Religion</th>
                                <th>Education</th>
                                <th>Occupation</th>
                                <th>Telephone Number</th>
                                <th>Cellphone Number</th>
                                <th>Email Address</th>
                                <th>New/Old</th>
                                <th>ACTIONS</th>
                            </thead>
                            <tbody>
                                @foreach($blood_donors as $v)
                                <tr>
                                    <td class="text-info">{{ $v->last_name }}, {{ $v->first_name }}</td>
                                    <td>{{ $v->birth_date }}</td>
                                    <td>{{ $v->civil_status }}</td>
                                    <td>{{ $v->age }}</td>
                                    <td>{{ $v->gender }}</td>
                                    <td>{{ $v->lot_no }}</td>
                                    <td>{{ $v->street }}</td>
                                    <td>{{ $v->barangay }}</td>
                                    <td>{{ $v->town_municipality }}</td>
                                    <td>{{ $v->province_city }}</td>
                                    <td>{{ $v->zip_code }}</td>
                                    <td>{{ $v->office_address }}</td>
                                    <td>{{ $v->nationality }}</td>
                                    <td>{{ $v->religion }}</td>
                                    <td>{{ $v->education }}</td>
                                    <td>{{ $v->occupation }}</td>
                                    <td>{{ $v->tel_no }}</td>
                                    <td>{{ $v->cell_no }}</td>
                                    <td style="text-align: center;">
                                        @if($v->is_new == 1)
                                        <span class="label label-success">NEW</span>
                                        @else
                                            <span class="label label-info">Old</span>
                                        @endif                                        
                                    </td>
                                    <td>
                                       
                                        <button class="btn btn-danger btn-flat btn-xs" onclick="deletesPatient('{{$v->id}}')"><i class="fa fa-trash"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> -->
            </div>
            <div class="col-xs-4">
                
                
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-edit-donor">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"> Edit Donation Information</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL::to('blood_register/postUpdateDonorReg') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="donor_history_id" id="donor_history_id">
                        <div class="form-group">
                            <label>Serial Number</label>
                            <input type="text" name="serial_number" id="edit_serial_numbers" class="form-control" required="">
                        </div>
                        <div class="form-group">
                          <label>Source:</label>
                          <select class="form-control select2" name="source" id="source" style="width: 100%;" required="">
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
                            <label>Category</label>
                            <input type="text" name="category"  id="category" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Donor No. per MBD</label>
                            <input type="text" name="donor_no_per_mbd"  id="donor_no_per_mbd" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>No. Bag of Blood Collected</label>
                            <input type="number" name="no_bag_collected"  id="no_bag_collected" class="form-control" required="">
                        </div>


                        <div class="form-group">
                            <label>ABO</label>
                            <input type="text" name="abo"  id="edit_abo" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Rh</label>
                            <input type="text" name="rh"  id="edit_rh" class="form-control" required="">
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
    <div class="modal fade" id="modal-new-old">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Is Donor New or Old ?</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success form-control" data-toggle="modal" data-target="#modal-new-donor">NEW</button>
                        </div>
                        <div class="col-md-6">
                            <button data-target="#modal-old" data-toggle="modal" class="btn btn-info form-control">OLD</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-deffered-donor">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Deffered Donor</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{URL::to('blood_register/setDeffered')}}">
                        {{ csrf_field()}}
                        <input type="hidden" name="history_id" id="history_id">
                        <div class="form-group">
                            <label>Reason:</label>
                            <textarea class="form-control" name="reason" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label>Remarks:</label>
                            <textarea class="form-control" name="remarks" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label>Suggestion:</label>
                            <textarea class="form-control" name="suggestion" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-flat">Ok</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-old">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Add Donation</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL::to('blood_register/postOldDonorSave') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Donor:</label>
                            <select class="form-control select2" name="donor_id" style="width: 100%;" required="">
                                @foreach($blood_donors as $v)
                                   
                                        <option value="{{ $v->id }}">{{ $v->last_name }}, {{ $v->first_name }}</option>
                                   
                                @endforeach
                                
                            </select>
                        </div>
                        <div class="form-group">
                          <label>Source:</label>
                          <select class="form-control select2" name="source" style="width: 100%;" required="">
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
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Donor No. per MBD</label>
                            <input type="text" name="donor_no_per_mbd" class="form-control" required="">
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
    <div class="modal fade" id="modal-new-donor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">New Blood Donor</h4>
                </div>
                <div class="modal-body" style="height: 490px; overflow: auto;">
                    <form method="POST" action="{{ URL::to('blood_register/save') }}">
                        
                       <!--  edutasdasd-->
                       {{ csrf_field() }}
                        <div class="form-group">
                            <label for="is_individual" class="">Donor Type:</label>
                            <select class="form-control" name="is_individual" id="edit_is_individual" onchange="showOrgs()">
                                <option value="0">Individual</option>
                                <option value="1">Group/Organization</option>
                            </select>
                        </div>
                        <div class="form-group" id="orgss">
                            <label for="org_id">Organization:</label>
                            {{ Form::select('org_id', $orgs, '', ['class'=>'form-control select2', 'style'=>'width: 100%;', 'required'=>'']) }}
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Personal Data:</legend>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>First Name:</label>
                                            <input required="" type="text" name="first_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Middle Name:</label>
                                            <input required="" type="text" name="middle_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Last Name:</label>
                                            <input required="" type="text" name="last_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Birthdate:</label>
                                            <input required="" type="date" name="birth_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Civil Status:</label>
                                            <select name="civil_status" class="form-control">
                                                <option>Married</option>
                                                <option>Single</option>
                                                <option>Divorced</option>
                                                <option>Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Age:</label>
                                            <input required="" type="number" name="age" min="0" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender:</label>
                                            <select class="form-control" name="gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Permanent Address:</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>No.</label>
                                        <input required="" type="number" name="lot_no" class="form-control" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Street</label>
                                        <input required="" type="text" name="street" class="form-control" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Barangay</label>
                                        <input required="" type="text" name="barangay" class="form-control" min="0">
                                    </div>                                   
                                </div>
                                <div class="row">
                                     <div class="col-md-4">
                                        <label>Town/Municipality</label>
                                        <input required="" type="text" name="town_municipality" class="form-control" min="0">
                                    </div>
                                     <div class="col-md-4">
                                        <label>Province/City</label>
                                        <input required="" type="text" name="province_city" class="form-control" min="0">
                                    </div>      
                                     <div class="col-md-4">
                                        <label>ZIP Code</label>
                                        <input required="" type="number" name="zip_code" min="0" class="form-control" min="0">
                                    </div>                                    
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend></legend>
                                <div class="form-group">
                                    <label>Office Address:</label>
                                    <input required="" type="text" name="office_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label>Nationality</label>
                                        <input required="" type="text" name="nationality" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Religion</label>
                                        <input required="" type="text" name="religion" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Education</label>
                                        <input required="" type="text" name="education" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Occupation</label>
                                        <input required="" type="text" name="occupation" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Contact No.</legend>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Telephone No.</label>
                                        <input required="" type="text" name="tel_no" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cellphone No.</label>
                                        <input required="" type="text" name="cell_no" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>E-Mail Address</label>
                                        <input required="" type="text" name="email_address" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
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
    <div class="modal fade" id="modal-edit-v-donor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit Blood Donor</h4>
                </div>
                <div class="modal-body" style="height: 490px; overflow: auto;">
                    <form method="POST" action="{{ URL::to('blood_register/update') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="is_individual" class="">Donor Type:</label>
                            <select class="form-control" disabled="" name="is_individual" id="edit_is_individual" onchange="showOrgs()">
                                <option value="0">Individual</option>
                                <option value="1">Group/Organization</option>
                            </select>
                        </div>
                        <div class="form-group" id="edit_orgss">
                            <label for="org_id">Organization:</label>
                            {{ Form::select('org_id', $orgs, '', ['class'=>'form-control select2', 'style'=>'width: 100%;']) }}
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Personal Data:</legend>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>First Name:</label>
                                            <input required="" type="text" name="first_name" id="edit_first_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Middle Name:</label>
                                            <input required="" type="text" name="middle_name" id="edit_edit_middle_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Last Name:</label>
                                            <input required="" type="text" name="last_name" id="edit_last_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Birthdate:</label>
                                            <input required="" type="date" name="birth_date" id="edit_birth_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Civil Status:</label>
                                            <select name="civil_status" id="edit_civil_status" class="form-control">
                                                <option>Married</option>
                                                <option>Single</option>
                                                <option>Divorced</option>
                                                <option>Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Age:</label>
                                            <input required="" type="number" name="age" id="edit_age" min="0" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender:</label>
                                            <select class="form-control" name="gender" id="edit_gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Permanent Address:</legend>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>No.</label>
                                        <input required="" type="number" name="lot_no" id="edit_lot_no" class="form-control" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Street</label>
                                        <input required="" type="text" name="street" id="edit_street" class="form-control" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Barangay</label>
                                        <input required="" type="text" name="barangay" id="edit_barangay" class="form-control" min="0">
                                    </div>                                   
                                </div>
                                <div class="row">
                                     <div class="col-md-4">
                                        <label>Town/Municipality</label>
                                        <input required="" type="text" name="town_municipality" id="edit_town_municipality" class="form-control" min="0">
                                    </div>
                                     <div class="col-md-4">
                                        <label>Province/City</label>
                                        <input required="" type="text" name="province_city" id="edit_province_city" class="form-control" min="0">
                                    </div>      
                                     <div class="col-md-4">
                                        <label>ZIP Code</label>
                                        <input required="" type="number" name="zip_code" id="edit_zip_code" min="0" class="form-control" min="0">
                                    </div>                                    
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend></legend>
                                <div class="form-group">
                                    <label>Office Address:</label>
                                    <input required="" type="text" name="office_address" id="edit_office_address" class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label>Nationality</label>
                                        <input required="" type="text" name="nationality" id="edit_nationality" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Religion</label>
                                        <input required="" type="text" name="religion" id="edit_religion" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Education</label>
                                        <input required="" type="text" name="education" id="edit_education" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Occupation</label>
                                        <input required="" type="text" name="occupation" id="edit_occupation" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend>Contact No.</legend>
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label>Telephone No.</label>
                                        <input required="" type="text" name="tel_no" id="edit_tel_no" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cellphone No.</label>
                                        <input required="" type="text" name="cell_no" id="edit_cell_no" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>E-Mail Address</label>
                                        <input required="" type="text" name="email_address" id="edit_email_address" class="form-control">
                                    </div>
                                </div>
                            </fieldset>
                        </div>       

                        <input type="hidden" name="donor_id" id="edit_donor_id">
                                       
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-modal-reservation-save" data-title="">Save</button>
                </form>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
<script type="text/javascript">
    $('#orgss').hide();
    setTimeout(function(){ $('#alerting_user').hide(200); }, 3000);
    function deletesPatient(id){
    var r = confirm("Are you sure that you want delete this donor ?");
    if (r == true) {
        $.get('<?php echo URL::to('/blood_register/delete'); ?>/'+id).done(function(){
          alert("Donor has been deleted.");
          location.reload();
        }).fail(function() {
          alert( "Ooppss, Sorry something wen't wrong. Please try again later." );
          location.reload();
        });
    }
    
  }
    $(document).ready(function(){
         $('#donor-table').DataTable({            
            "columnDefs":[
                { "visible": false, "targets": [1,5,6,7,8,9,10,11,12,13,14,15,16,17] },
                { "targets": 19, "orderable": false }
            ]
         });
         $('#today-donor-table').DataTable({            
            /*"columnDefs":[
                { "visible": false, "targets": [1,5,6,7,8,9,10,11,12,13,14,15,16,17] },
                { "targets": 19, "orderable": false }
            ]*/
         });


    });

    function showOrgs(){
       var val = $("#is_individual").val();
       if(val == 1){
            $('#orgss').show();
       }else{
        $('#orgss').hide();
       }

    }

    function preSetDeffered(id){
        var r = confirm("Are you that you want deffered this Donor?");
        if(r){            
            // $('#myModal').modal('toggle');
            $('#modal-deffered-donor').modal('show');
            $('#history_id').val(id);
            // $('#myModal').modal('hide');
        }
    }
    function setDeffered(id, reason){

    }

    function editDailyDonor(id){
        $('#modal-edit-donor').modal('show');
        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "{{ URL::to('/blood_register/getInfo')}}/"+id,
          "method": "GET",
          "headers": {
            "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
            "cache-control": "no-cache",
            "postman-token": "96d5e853-f5cd-cd7d-08f0-639acf7d2a08"
          }
        }

        $.ajax(settings).done(function (response) {
            var data = JSON.parse(JSON.stringify(response));
            
           
           $("#donor_history_id").val(data.id);
           $("#source").val(data.source);
           $("#category").val(data.category);
           $("#donor_no_per_mbd").val(data.donor_no_per_mbd);
           $("#no_bag_collected").val(data.no_bag_collected);
           $('#edit_serial_numbers').val(data.serial_number);
           $('#edit_rh').val(data.rh);
           $('#edit_abo').val(data.abo);

        });

    }


    function editBloodDonor(id){        
        var settings = {
          "async": true,
          "crossDomain": true,
          "url": "{{ URL::to('ajax/getDonorInfo') }}/"+id,
          "method": "GET",
          "headers": {
            "authorization": "Basic anBsdC5kZXZlbG9wZXIwMDFAZ21haWwuY29tOjEyMw==",
            "cache-control": "no-cache",
            "postman-token": "f5ed20f0-c713-ddbe-ffd6-1d9b4f830c91"
          }
        }

        $.ajax(settings).done(function (response) {
            var data = JSON.parse(JSON.stringify(response));
            
            alert(data.serial_number);
            $('#edit_donor_id').val(data.id);
            $('#edit_is_individual').val(data.is_individual);
            $('#edit_first_name').val(data.first_name);
            $('#edit_edit_middle_name').val(data.middle_name);
            $('#edit_last_name').val(data.last_name);
            $('#edit_birth_date').val(data.birth_date);
            $('#edit_civil_status').val(data.civil_status);
            $('#edit_age').val(data.age);
            $('#edit_gender').val(data.gender);
            $('#edit_lot_no').val(data.lot_no);
            $('#edit_street').val(data.street);
            $('#edit_barangay').val(data.barangay);
            $('#edit_town_municipality').val(data.town_municipality);
            $('#edit_province_city').val(data.province_city);
            $('#edit_zip_code').val(data.zip_code);
            $('#edit_office_address').val(data.office_address);
            $('#edit_nationality').val(data.nationality);
            $('#edit_religion').val(data.religion);
            $('#edit_education').val(data.education);
            $('#edit_occupation').val(data.occupation);
            $('#edit_tel_no').val(data.tel_no);
            $('#edit_cell_no').val(data.cell_no);
            $('#edit_email_address').val(data.email_address);
            

            if(data.is_individual == '0' || data.is_individual == 0){
                $('#edit_orgss').remove();
            }
          $("#modal-edit-v-donor").modal("show");
            
        }).fail(function(){
            alert("Oops, something wen't wrong.");
        });
        
    }

    
    !isUserValid() && disableButtons();

</script>
@endsection

