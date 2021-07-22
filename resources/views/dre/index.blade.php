@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reservation Entry
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Reservation Entry</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List Reservation Entry's</h3>

          <div class="box-tools pull-right">
            <a href="{{ URL::to('/dre/add')}}" class="btn btn-danger btn-flat btn-xs">Add New Reservation</a>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">                    
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Control #</th>
                  <th>Time Reserved</th>
                  <th>Time Released</th>
                  <th>Availability</th>
                  <th>Name of patient/HD</th>
                  <th>Remarks</th>
                  <th>Hospital</th>
                  <th>Blood Type</th>
                  <th># Component I</th>
                  <th>Component I</th>
                  <th># Component II</th>
                  <th>Component II</th>
                  <th># Component III</th>
                  <th>Component III</th>
                </tr>
                </thead>
                <tbody>
                @if(count($transactions)>0)
                  @foreach($transactions as $v)
                    <tr>
                      <td>{{ $v->control_no }}</td>
                      <td>{{ $v->time_reserved }}</td>
                      <td>{{ $v->time_released }}</td>
                      <td>{{ $v->availability }}</td>
                      <td>{{ $v->patient_id }}</td>
                      <td>{{ $v->remarks }}</td>
                      <td>{{ $v->hospital_id }}</td>
                      <td>{{ $v->blood_type }}</td>
                      <td>{{ $v->ci_qty }}</td>
                      <td>{{ $v->ci_comp }}</td>
                      <td>{{ $v->cii_qty }}</td>
                      <td>{{ $v->cii_comp }}</td>
                      <td>{{ $v->ciii_qty }}</td>
                      <td>{{ $v->ciii_comp }}</td>
                    </tr>
                  @endforeach
                @endif
                
                </tbody>
                <tfoot>
                <tr>
                  <th>Control #</th>
                  <th>Time Reserved</th>
                  <th>Time Released</th>
                  <th>Availability</th>
                  <th>Name of patient/HD</th>
                  <th>Remarks</th>
                  <th>Hospital</th>
                  <th>Blood Type</th>
                  <th># Component I</th>
                  <th>Component I</th>
                  <th># Component II</th>
                  <th>Component II</th>
                  <th># Component III</th>
                  <th>Component III</th>
                </tr>
                </tfoot>
              </table>
              </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <form method="POST" action="dre/generateDre">
            {{ csrf_field() }}
            <input type="hidden" name="filterDate" value="{{ date('Y-m-d') }}">
            <button type="submit" class="btn btn-danger">Generate Daily Reservation Entry</button>
          </form>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
@section('custom_js')
<script type="text/javascript">
    $(function () {
    $(".select2").select2();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollX": true
    });
    $('#example2 tbody').on('click', 'tr', function () {
        console.log(table.row(this).data());
    });
  });
</script>
@endsection