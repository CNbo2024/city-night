<ul class="list-group sv_list_group mt-0 mb-3">
	<li>
		<a class="btn  text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'basics'?'vbtn-outline-success active-side':''}} {{ $missed['basics'] == 1 ? '' : 'step-inactive'  }} " href="{{$result->status != ""? url("listing/$result->id/basics"):"#"}}">{{trans('messages.listing_sidebar.basic')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'description'?'vbtn-outline-success active-side':' '}} {{ $missed['description'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/description"):"#"}}">{{trans('messages.listing_sidebar.description')}}</a>
	</li>
	
	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 text-capitalize {{ Request::segment(3) == 'details'?'vbtn-outline-success active-side':' '}} {{ $missed['description'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/details"):"#"}}">{{trans('messages.listing_description.detail')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'location'?'vbtn-outline-success active-side':' '}} {{ $missed['location'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/location"):"#"}}"> {{trans('messages.listing_sidebar.location')}}</a>
	</li>
	
	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3  {{ Request::segment(3) == 'amenities'?'vbtn-outline-success active-side':''}} {{ $result->amenities == null ? 'step-inactive' : ''  }}" href="{{$result->status != ""? url("listing/$result->id/amenities"):"#"}}"> {{trans('messages.listing_sidebar.amenities')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'photos'?'vbtn-outline-success active-side':' '}} {{ $missed['photos'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/photos"):"#"}}"> {{trans('messages.listing_sidebar.photos')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'pricing'?'vbtn-outline-success active-side':' '}} {{ $missed['pricing'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/pricing"):"#"}}"> {{trans('messages.listing_sidebar.price')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3  {{ Request::segment(3) == 'booking'?'vbtn-outline-success active-side':' '}} {{ $missed['booking'] == 1 ? '' : 'step-inactive'  }}" href="{{$result->status != ""? url("listing/$result->id/booking"):"#"}}"> {{trans('messages.listing_sidebar.booking')}}</a>
	</li>

	<li>
		<a class="btn text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3 {{ Request::segment(3) == 'calendar'?'vbtn-outline-success active-side':' '}}" href="{{$result->status != ""? url("listing/$result->id/calendar"):"#"}}">{{trans('messages.listing_sidebar.calender')}}</a>
	</li>
</ul>