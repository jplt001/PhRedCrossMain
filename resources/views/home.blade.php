@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 class="dashboard-reserved-widget"><i class="fa fa-circle-o-notch fa-spin"></i></h3>
              <p>Reserves</p>
            </div>
            <div class="icon">
              <i class="fa fa-calendar-o"></i>
            </div>
            <a href="{{URL::to('/bloodbank')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 class="dashboard-released-widget"><i class="fa fa-circle-o-notch fa-spin"></i></h3>
              <p>Released</p>
            </div>
            <div class="icon">
              <i class="fa fa-rocket"></i>
            </div>
            <a href="{{URL::to('/bloodbank')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 class="dashboard-daily-reserved-widget"><i class="fa fa-circle-o-notch fa-spin"></i></h3>
              <p>Today's Reservations</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="{{URL::to('/bloodbank')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 class="dashboard-patients-widget"><i class="fa fa-circle-o-notch fa-spin"></i></h3>
              <p>Overall Patients Record</p>
            </div>
            <div class="icon">
              <i class="fa fa-wheelchair"></i>
            </div>
            <a href="{{URL::to('/patient')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
        <div class="row">
            <div class="dashboard-overlay">
                <i class="fa fa-circle-o-notch fa-spin fa-3x"></i>
            </div>
            <div>
                <div class="col-xs-12">
                    <span style="font-size: 18px">Blood Availability Status</span>
                    <!-- <div class="pull-right">
                      @if( acl_roles(Auth::user()->user_type, 'forecast') )
                        <a href="{{URL::to('/forecast')}}" class="btn btn-primary btn-xs"><i class="fa fa-chart"></i> To Forecasting</a>
                      @endif
                    </div> -->
                    <div style="height: 1px; background-color: #d0d0d0">
                </div>
                <div class="box box-info">
                  <div class="box-body">
                    <table id="table-blood-availability" class="table">
                        <thead>
                          <th>SI No.</th>                        
                          <th>A+ve</th>
                          <th>A-ve</th>
                          <th>B+ve</th>
                          <th>B-ve</th>
                          <th>AB+ve</th>
                          <th>AB-ve</th>
                          <th>O+ve</th>
                          <th>O-ve</th>
                          <th>Total</th>
                          <!-- <th>Update On</th> -->
                          <th>Collection Type</th>
                        </thead>
                      </table>
                  </div>
                </div>
            </div>
        </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
