@extends('template')
@push('scripts')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
<script type="text/javascript">
		$(function() {
			$('.faq-heading').click(function () {
                $(this).parent('li').toggleClass('the-active').find('.faq-text').slideToggle();
            });
		});
</script>
@endpush

@section('main')

<?php 
$current_url = url()->current();
$abt_url    = url('/').'/about';
$become_host = url('/').'/become-host';
$contact_url    = url('/').'/contact-us';

?>

<main role="main" id="site-content" <?php if($current_url!=$become_host){ ?> class="margin-top-120" <?php } ?>>
    <div <?php if($current_url!=$become_host){ ?> class="container-fluid container-fluid-90 mb-5 pb-5" <?php } ?>>
        {!! $content !!}
    </div>
    <br>
    
    @if($current_url==$abt_url)
	@if(!$testimonials->isEmpty())
	<section class="testimonialbg pb-70">
		<div class="testimonials">
			<div class="container">
				<div class="row">
					<div class="recommandedhead section-intro text-center mt-70">
						<p class="animated fadeIn text-24 text-color font-weight-700 m-0">{{ trans('messages.home.say_about_us') }}</p>
						<p class="mt-2">{{trans('messages.home.people_say')}}</p>
					</div>
				</div>

				<div class="row mt-5">
					@foreach($testimonials as $testimonial)
					<?php $i = 0; ?>
						<div class="col-md-4 mt-4">
							<div class="item h-100 card-1">
								<img src="{{$testimonial->image_url}}" alt="{{$testimonial->name}}">
								<div class="name">{{$testimonial->name}}</div>
									<small class="desig">{{$testimonial->designation}}</small>
									<p class="details">{{ substr($testimonial->description, 0, 200) }} </p>
									<ul>
										@for ($i = 0; $i < 5; $i++)
											@if($testimonial->review >$i)
												<li><i class="fa fa-star secondary-text-color" aria-hidden="true"></i></li>
											@else
												<li><i class="fa fa-star rating" aria-hidden="true"></i></li>
											@endif
										@endfor                  
									</ul>
							</div>
						</div>
					@endforeach
				</div>
			</div>  
		</div>
	</section>
	@endif
	@endif
	
	
	
	 @if($current_url==$contact_url)
	


<div class="container">
   <div class="row pb-5 mt-5">
     <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1  p-0 pb-5 mb-5">
         @if(Session::has('success'))
              <div class="alert alert-success">
        	    {{ Session::get('success') }}
               </div>
           @endif
         <div class="card-header"><h2 class="font-weight-600">{{trans('messages.experience.contact')}}</h2></div>
       <div class="card-body card-user card-default mb-5">
            <form method="POST" action="{{ route('addContact') }}" enctype="multipart/form-data">
             {{csrf_field()}}
             <div class="row mt-5">
                 <div class="col-md-2">
                     <label class="font-weight-600">  {{trans('messages.trips_receipt.name')}}</label>
                 </div>
                 <div class="col-md-10">
                     <input type="text" class="form-control" placeholder="" name="username" required>
                 </div>
             </div>
             <div class="row mt-5">
                 <div class="col-md-2">
                     <label class="font-weight-600"> {{trans('messages.account.email')}}</label>
                 </div>
                 <div class="col-md-10">
                      <input type="email" class="form-control" placeholder="" name="useremail" required>
                 </div>
             </div>
             <div class="row mt-5">
                 <div class="col-md-2">
                     <label class="font-weight-600">{{trans('messages.booking_my.send_message')}}</label>
                 </div>
                 <div class="col-md-10">
                      <textarea class="form-control textarea contact-textarea" placeholder="" name="message" required></textarea>
                 </div>
             </div>
			 
			 {!! NoCaptcha::renderJs() !!}

			 <div class="row mt-5 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
				 <div class="col-md-2">
				     <label class="font-weight-600">{{trans('messages.experience.captcha')}}</label>
				 </div>
				<div class="col-md-10">
				{!! app('captcha')->display() !!}
					@if ($errors->has('g-recaptcha-response'))
						<span class="help-block text-danger">
							<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
						</span>
					@endif
				</div>
			</div>
			 
             <div class="row mt-5">
              <div class="col-md-12 text-right">
                 <button type="submit" class="btn vbtn-default pl-5 pr-5 text-14">{{trans('messages.header.inbox')}}</button>
               </div>
             </div>
           </form>
        
       </div>
     </div>
   </div>
</div>

	 
		
	 @endif
    
</main>
@stop

<style>
    img {
        width:100%;
        height: auto;
    }
    .header_area .navbar{
        background:#fff !important;
    }
   
</style>