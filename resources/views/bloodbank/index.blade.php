@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
			Blood Bank
			<small> View and manage blood inventory</small>
		</h1>
        <div class="section-tools text-right">
            <a href="#" id="btnHelp"><i class="fa fa-question-circle"></i> Help</a>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-lg-8" style="transition: 0.5s">
                <div class="box box-primary box-reservation">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reservations</h3>
                        <div class="pull-right">
                            <button class="toggle-modal-reservation btn btn-primary btn-xs disabled"><i class="fa fa-plus"></i> Add New Reservation</button>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 550px"></div>
                </div>
            </div>
            <div class="col-xs-12 col-lg-4" style="transition: 0.5s">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs text-right ui-sortable-handle">
                        <li class="header"><i class="fa fa-inbox"></i> Unit Counts</li>
                        <li class="inventory-tab active" data-tab-name="RESERVED"><a href="#inventory-reserved" data-toggle="tab" aria-expanded="false">Reserved</a></li>
                        <li class="inventory-tab" data-tab-name="RELEASED"><a href="#inventory-released" data-toggle="tab" aria-expanded="true">Released</a></li>
                        <li class="inventory-tab" data-tab-name="STORAGE"><a href="#inventory-storage" data-toggle="tab" aria-expanded="true">Storage</a></li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="inventory-reserved">
                            <table class="table">
                                <thead class="inventory-tab-header"></thead>
                                <tbody class="inventory-tab-body-reserved">
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="inventory-released">
                            <table class="table">
                                <thead class="inventory-tab-header"></thead>
                                <tbody class="inventory-tab-body-released"></tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="inventory-storage">
                            <table class="table">
                                <thead class="inventory-tab-header"></thead>
                                <tbody class="inventory-tab-body-storage"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-reservation">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 form-group modal-reservation-serology-container">
                            <label>Serial Number</label>
                            <select class="form-control modal-reservation-input" id="modal-reservation-serology" style="width:100%">
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 form-group modal-reservation-diagnosis-container">
                            <label>Diagnosis</label>
                            <input type="text" class="form-control" id="modal-reservation-diagnosis">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Patient</label>
                            <span class="modal-reservation-patient-mode-container pull-right" hidden>
                                <div class="btn-group colors" data-toggle="buttons">
                                    <label class="btn btn-primary btn-xs active">
                                        <input type="radio" name="patient-mode" value="EXISTING" autocomplete="off" checked> Existing
                                    </label>
                                    <label class="btn btn-primary btn-xs">
                                        <input type="radio" name="patient-mode" value="NEW" autocomplete="off"> New Patient
                                    </label>
                                </div>
                            </span>
                            <div class="modal-reservation-select-patient-container" hidden>
                                <select class="form-control modal-reservation-input" id="modal-reservation-patient-id" style="width:100%"></select>
                            </div>
                            <div class="row modal-reservation-new-patient-container" hidden>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Lastname" id="modal-reservation-patient-lastname">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Firstname" id="modal-reservation-patient-firstname">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control" placeholder="Middlename" id="modal-reservation-patient-middlename">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-12 text-center modal-reservation-blood-type-container" hidden>
                            <div class="blood-type-buttons col-xs-6" data-toggle="buttons">
                                @foreach($blood_types as $blood_type)
                                    <label class="btn btn-default btn-flat">
                                        <input type="radio" name="blood_types" value="{{ $blood_type->id }}">
                                        <!-- <small> -->
                                            <b>
                                                {{ substr($blood_type->type, 0, (strlen($blood_type->type)-1)) }}
                                                <sup>{{ substr($blood_type->type, (strlen($blood_type->type)-1)) }}</sup>
                                            </b>
                                        <!-- </small> -->
                                    </label>
                                @endforeach
                            </div>
                            <div class="form-group col-xs-6">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input type="number" placeholder="Age" id="modal-reservation-patient-age" class="form-control">
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary">
                                                <input type="radio" name="reservation-gender" value="1">Male
                                            </label>
                                            <label class="btn btn-primary">
                                                <input type="radio" name="reservation-gender" value="0">Female
                                            </label>
                                        </div>
                                        <!-- <div class="btn-group colors" data-toggle="buttons">
                                            <label class="btn btn-primary btn-xs active">
                                                <input type="radio" name="patient-mode" value="EXISTING" autocomplete="off" checked> Existing
                                            </label>
                                            <label class="btn btn-primary btn-xs">
                                                <input type="radio" name="patient-mode" value="NEW" autocomplete="off"> New Patient
                                            </label>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Branch</label>
                            <select class="form-control modal-reservation-input" id="modal-reservation-branch-id">
                                <option selected value="">-</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Hospital</label>
                            <select class="form-control modal-reservation-input" id="modal-reservation-hospital-id">
                                <option selected value="">-</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->hospital_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Component</th>
                                    <th>Number of Units</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="component-list-container">
                            </tbody>
                            <tbody class="component-input-row" style="border-top:0px;">
                                <tr>
                                    <td>
                                        <select class="form-control" id="input-component-id">
                                            <option selected value="">-</option>
                                            @foreach($components as $component)
                                                <option value="{{ $component->id }}" data-id="{{ $component->id }}">{{ $component->code }} - {{ $component->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input id="input-component-qty" class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="2" placeholder="Default value: 1">
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-add-component btn btn-primary btn"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Remarks <i>(Optional)</i></label>
                        <textarea class="modal-reservation-input form-control" rows="3" style="resize: none" id="modal-reservation-remarks"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-modal-reservation-save" data-title=""></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-reservation-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Deleting Reservation</h4>
                </div>
                <div class="modal-body text-center">
                    <h4>Delete this reservation?</h4>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <button class="btn btn-primary btn-delete-reservation">Continue</button>
                            <button class="btn btn-default" type="button" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-reservation-release">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Release Reservation</h4>
                </div>
                <div class="modal-body text-center">
                    <h4>Release this reservation?</h4>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <button class="btn btn-primary btn-release-reservation">Continue</button>
                            <button class="btn btn-default" type="button" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-reservation-confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Action confirmation</h4>
                </div>
                <div class="modal-body text-center">
                    <h4></h4>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <button class="btn btn-primary btn-continue-confirm">Continue</button>
                            <button class="btn btn-default" type="button" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-reservation-help">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Help</h4>
                </div>
                <div class="modal-body">
                    <table class="table no-border">
                        <tbody>
                            <tr>
                                <td><b>WB</b></td>
                                <td>Whole Blood</td>
                            </tr>
                            <tr>
                                <td><b>PRBC</b></td>
                                <td>Packed Red Blood Cell</td>
                            </tr>
                            <tr>
                                <td><b>FFP</b></td>
                                <td>Fresh Frozen Plasma</td>
                            </tr>
                            <tr>
                                <td><b>Plt Con</b></td>
                                <td>Platelet Concentrate</td>
                            </tr>
                            <tr>
                                <td><b>Cpt</b></td>
                                <td>Cryoprecipitate</td>
                            </tr>
                            <tr>
                                <td><b>Cst</b></td>
                                <td>Cryosupernate</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
