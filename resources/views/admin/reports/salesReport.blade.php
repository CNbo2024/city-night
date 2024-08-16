@extends('admin.template')
@section('main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
     <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      
      
      <div class="row">
           <div class="box">
                <div class="box-body">
                    
                    <form class="form-horizontal" enctype='multipart/form-data' action="{{ url('admin/sales-report') }}" method="GET" accept-charset="UTF-8">
                        {{ csrf_field() }}
                        <div class="col-lg-4 col-xs-12">
                            <label>Select Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="Property" <?php if($type=='Property') { echo "selected"; } ?>>Property</option>
                                <option value="Experience" <?php if($type=='Experience') { echo "selected"; } ?>>Experience</option>
                            </select>
                        </div>
                         <div class="col-md-1 col-sm-2 col-xs-4 mt-5">
                        <br>
                        <button type="submit" name="btn" class="btn btn-primary">Filter</button>
                      </div>
                    </form>
                </div>
            </div>
      </div>
      
      
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner"> 
              <h3>{!! moneyFormat($default_cur_code->org_symbol, @$totalIncome) !!}</h3>

              <p>Total Income</p>
              <p></p>
            </div>
            <p class="small-box-footer">Income from Past 12 Months</p>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3>{{ @$totalNights }}</h3>

              <p>Total Nights</p>
            </div>
          
            <p class="small-box-footer">Reserved Nights from Past 12 Months</p>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ @$totalReservations }}</h3>

              <p>Total Reservations</p>
            </div>
           
            <p class="small-box-footer">Reservations from Past 12 Months</p>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- /.content -->
      <div class="row">
          <div class="col-md-12">
        <div id="container" class="sale-container"></div>
        </div>
      </div>

      <!-- /.content -->
      <div class="row">
        
      </div>

    </section>
    <style>
          .highcharts-title {
                color: #fff !important;
                fill: #fff !important;
        }
      </style>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop

@section('validate_script')
<script src="{{URL::to('/')}}/public/backend/plugins/highcharts/highcharts.js"></script>
<script src="{{URL::to('/')}}/public/backend/plugins/highcharts/exporting.js"></script>

<script>
	Highcharts.chart('container', {
    chart: {
        type: 'line',
        backgroundColor: '#212130',
    },
    title: {
        text: 'Sales of Past 12 Months'
    },
    subtitle: {
        text: 'Total Income {{$default_cur_code->code}} {{$totalIncome}} for {{$totalNights}} Nights'
    },
    xAxis: {
        categories: jQuery.parseJSON('{!! $months !!}')
    },
    yAxis: {
        title: {
            text: 'Nights per Month'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    legend: {
        itemStyle: {
            color: '#ed3615'
        },
        
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    }, // optional
    series: [{
        name: 'Nights',
        color: '#ed3615',
        data: jQuery.parseJSON('{!! $monthlyNights !!}') //[22.0, 4.9, 4.5, 54.5, 8.4, 11.5, 24.2, 21.5, 28.3, 28.3, 12.9, 3.6]
    }]
});
</script>
@endsection