@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Branch
      <small> View and manage branch</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('/home') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-building" aria-hidden="true"></i> Branch</li>        
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Branch</h3>

          <div class="box-tools pull-right">
            <a href="{{ URL::to('/branch/create')}}" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-building" aria-hidden="true"></i> Add Branch</a>
            <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button> -->
          </div>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>Branch Name</th>
                  <th>Address</th>
                  <th>Contact Number</th>                  
                  <th><small>ACTIONS</small></th>
                </tr>
                </thead>
                <tbody>
                @if(count($branch_list) > 0)

                  @foreach($branch_list as $v)
                  <tr>
                    <td><!-- <a href="" title="{{ $v->branch_name }}"> -->{{ $v->branch_name }}<!-- </a> --></td>
                    <td>{{ $v->address }}</td>
                    <td>{{ $v->contact_no }}</td>
                    <td style="text-align: center;">
                      
                      <a href="{{ URL::to('/branch/edit') }}/{{ $v->id}}" class="btn btn-success btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                      <a href="" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

                    </td>
                  </tr>
                  @endforeach
                  
                @endif
                </tbody>
                <tfoot>
                <tr>
                  <th>Branch Name</th>
                  <th>Address</th>
                  <th>Contact Number</th>                  
                  <th><small>ACTIONS</small></th>
                </tr>
                </tfoot>
              </table>
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
<script type="text/javascript">
  var table = $('#example1').DataTable({
      "scrollY": "800px",
      "scrollCollapse": true,
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "scrollX": true
    });
   
</script>
@endsection