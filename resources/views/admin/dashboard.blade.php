@extends('admin.template')
@section('main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper svdashboard">
     <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
         <div class="col-md-6">
            <h3 class="text-white col-md-12">Users</h3>
            <div class="col-lg-6">
              <div class="small-box svbg_blue">
               
                <div class="inner">
                    <h3>{{ $total_users_count }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="svicon">
                    <i class="ion ion-person-add"></i>
                </div>
                <!--<a href="{{ url('admin/customers') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
         
            <div class="col-lg-6">
              <div class="small-box svbg_orange">
                <div class="inner">
                  <h3>{{ $today_users_count }}</h3>
                  <p>Today Users</p>
                </div>
                 <div class="svicon">
                  <i class="ion ion-person-add"></i>
                </div>
                <!--<a href="{{ url('admin/customers') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h3 class="text-white col-md-12">Revenue</h3>
            <div class="col-lg-6">
              <div class="small-box svbg_violet">
                <div class="inner">
                  <h3>{!! moneyFormat($default_cur_code->org_symbol,@$totalIncome) !!}</h3>
    
                  <p>Total Income</p>
                </div>
                 <div class="svicon">
                  <i class="fa fa-money"></i>
                </div>
              </div>
            </div>
        
            <div class="col-lg-6">
              <div class="small-box svbg_teal">
                <div class="inner">
                  <h3>{{ @$totalNights }}</h3>
                  <p>Total Nights</p>
                </div>
                 <div class="svicon">
                  <i class="fa fa-bed"></i>
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h3 class="text-white col-md-12">Property</h3>
            <div class="col-lg-6">
              <div class="small-box svbg_green">
                  <div class="inner">
                  <h3>{{ $total_property_count }}</h3>
                  <p>Total Property</p>
                </div>
                <div class="svicon">
                  <i class="fa fa-home"></i>
                </div>
                <!--<a href="{{ url('admin/properties') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            
            <div class="col-lg-6">
                <div class="small-box svbg_purple">
                    <div class="inner">
                      <h3>{{ $today_property_count }}</h3>
                      <p>Today Property</p>
                    </div>
                    <div class="svicon">
                      <i class="fa fa-home"></i>
                    </div>
                    <!--<a href="{{ url('admin/properties') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
                </div>
            </div>
        
        </div>
       
  
        <div class="col-md-6">
            <h3 class="text-white col-md-12">Experience</h3>
            <div class="col-lg-6">
                <div class="small-box svbg_green">
                    <div class="inner">
                      <h3>{{ $total_experience_count }}</h3> 
                      <p>Total Experience</p>
                    </div>
                   <div class="svicon">
                      <i class="fa fa-star"></i>
                    </div>
               </div>
            </div>
            
            <div class="col-lg-6">
              <div class="small-box svbg_pink">
                <div class="inner">
                  <h3>{{ $today_experience_count }}</h3> 
                  <p>Today Experience</p>
                </div>
               <div class="svicon">
                  <i class="fa fa-star"></i>
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <h3 class="text-white col-md-12">Reservation</h3>
            <div class="col-lg-6">
              <div class="small-box svbg_pink">
                <div class="inner"> 
                  <h3>{{ $total_reservations_count }}</h3>
                  <p>Total Reservations</p>
                </div> 
                <div class="svicon">
                  <i class="fa fa-calendar-check-o"></i>
                </div>
                <!--<a href="{{ url('admin/bookings') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
              </div>
            </div>
            
            <div class="col-lg-6">
              <div class="small-box svbg_yellow"> 
                <div class="inner">
                  <h3>{{ $today_reservations_count }}</h3>
                  <p>Today Reservations</p>
                </div>
                 <div class="svicon">
                  <i class="fa fa-calendar-check-o"></i>
                </div>
                <!--<a href="{{ url('admin/bookings') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
                </div>
            </div>
        </div>
        

        
        
        
     
        
      </div>
      <!-- /.row -->
      <!-- /.content -->
      <style>
          .highcharts-title {
                color: #fff !important;
                fill: #fff !important;
        }
      </style>
      <br>
      <div class="row">
        <div class="col-md-8">
            <div id="container" class="sale-container"></div>
        </div>
        
        
        <div class="col-md-4">
          <!-- LINE CHART -->
          <div class="box box-info" style="border-top-color:transparent;">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Bookings</h3>
            </div>
            <div class="box-body" style="margin-top:0">
            <div class="table-responsive">
              <table class="table">
                  <thead>
                    <tr>
                      <th>Guest Name</th>
                      <th>Amount</th>
                      <th>Date</th>
                      <th width="5%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(!empty($bookingList))
                    @foreach($bookingList  as $booking)
                      <tr>
                        <td><a href="{{ url('admin/edit-customer/'.$booking->user_id) }}">{{$booking->guest_name}}</a></td>
                        <td>{!! moneyFormat($booking->currency->symbol, $booking->total_amount) !!}</td>
                        <td>{{dateFormat($booking->created_at)}}</td>
                        <td>{{$booking->status}}</td>
                      </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        
      </div>
      <br>
      
      
      
      <div class="row">
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-info" style="border-top-color:transparent;">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Property</h3>
            </div>
            <div class="box-body">
           <div class="table-responsive">
              <table class="table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Host Name</th>
                      <th width="15%">Date</th>
                      <th width="5%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(!empty($propertiesList))
                    @foreach($propertiesList  as $property)
                      <tr>
                        <td><a href="{{url('admin/listing/'.$property->properties_id).'/basics'}}" >{{$property->property_name}}</a></td>
                        <td><a href="{{ url('admin/edit-customer/'.$property->host_id) }}">{{$property->first_name.' '.$property->last_name}}</a></td>
                        <td>{{dateFormat($property->property_created_at)}}</td>
                        <td>{{$property->property_status}}</td>
                      </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
           </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      
       <div class="row">
        <div class="col-md-12">
          <!-- LINE CHART -->
          <div class="box box-info" style="border-top-color:transparent;">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Experience</h3>
            </div> 
            <div class="box-body">
           <div class="table-responsive">
              <table class="table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Host Name</th>
                      <th width="15%">Date</th>
                      <th width="5%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(!empty($experience))
                    @foreach($experience  as $property)
                      <tr>
                        <td><a href="{{url('admin/experience/'.$property->properties_id).'/basics'}}" >{{$property->property_name}}</a></td>
                        <td><a href="{{ url('admin/edit-customer/'.$property->host_id) }}">{{$property->first_name.' '.$property->last_name}}</a></td>
                        <td>{{dateFormat($property->property_created_at)}}</td>
                        <td>{{$property->property_status}}</td>
                      </tr>
                      @endforeach 
                    @endif
                  </tbody>
                </table>
           </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>

      <!-- /.content -->
      <div class="row">
        
      </div>

    </section>
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
        type: 'spline',
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
        borderWidth: 0,
        color: '#ed3615',
    }, // optional
    series: [{
        name: 'Nights',
        color: '#ed3615',
        data: jQuery.parseJSON('{!! $monthlyNights !!}') //[22.0, 4.9, 4.5, 54.5, 8.4, 11.5, 24.2, 21.5, 28.3, 28.3, 12.9, 3.6]
    }]
});
</script>
@endsection