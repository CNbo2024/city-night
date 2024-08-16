@extends('template')

@section('main')
	<style>
		.fc-event.fc-event-start.fc-event-end.fc-event-today.fc-daygrid-event.fc-daygrid-block-event.fc-h-event {
			cursor: pointer;
		}
	</style>

	<div class="margin-top-85">
		<div class="row m-0">
			@include('host.menu')

        	<div class="col-12 col-md-9">
		    	<div id="calendar" class="mt-5 mb-5 pb-5"></div>
			</div>

			<div class="col-12 col-md-3 mt-5 pt-5">
        		<div class="card mt-5">
        			<div class="card-header">
        				{{ __('messages.prices') }}
        			</div>

        			<div class="card-body">
        				<form action="/property-price-date" method="POST">
        					@csrf

        					@if(session('success'))
        						<div class="alert alert-success">{{ session('success') }}</div>
        					@endif

        					<input type="hidden" name="dates" class="input-block-selected-dates">

        					<button class="btn btn-primary btn-block mb-2 btn-block-selected-dates" type="button">
        						{{ __('messages.block_selected_dates') }}
        					</button>

	        				<div class="form-group">
	        					<label for="property_id">{{ __('messages.properties') }}</label>
	        					<select onchange="window.location.href = '?property_id=' + this.value" required name="property_id" class="form-control">
	        						<option value=""></option>

	        						@foreach($properties as $property)
				        				<option {{ request()->property_id == $property->id ? 'selected' : '' }} data-week="{{ $property->week }}" data-month="{{ $property->month }}" data-price="{{ $property->property_price->price }}" value="{{ $property->id }}">{{ $property->name }}</option>
				            		@endforeach
	        					</select>
	        				</div>

	        				<div class="form-group">
	        					<label for="date">{{ __('messages.date') }}</label>
	        					<input required type="date" name="date" class="form-control">
	        				</div>

	        				<div class="form-group">
	        					<label for="price">{{ __('messages.price') }}</label>

	        					<div class="input-group">
	        						<div class="input-group-prepend">
	        							<span class="input-group-text">{{ 'Bs.' }}</span>
	        						</div>

	        						<input required type="number" name="price" class="form-control">
	        					</div>
	        				</div>

	        				<div class="form-group">
	        					<div class="smart-price mb-2">
	        						@if(request()->price)
	        							{{ 'Precio sugerido: Bs. ' . request()->price }}
	        						@endif
	        					</div>

	        					<div class="row mb-4 show-min-max-smart-price">
	        						<div class="col-md-6">
	        							<label for="smart_price_min">{{ __('messages.min') }}</label>
	        							<input type="number" class="form-control" name="smart_price_min" value="{{ request()->min }}">
	        						</div>

	        						<div class="col-md-6">
	        							<label for="smart_price_max">{{ __('messages.max') }}</label>
	        							<input type="number" class="form-control" name="smart_price_max" value="{{ request()->max }}">
	        						</div>
	        					</div>

	        					<input type="checkbox" name="smart_price" {{ request()->price ? 'checked' : '' }} > {{ __('messages.smart_price') }}
	        				</div>

	        				<div class="form-group">
	        					<label for="weekly">{{ __('messages.discount_special') }}</label>

	        					<div class="input-group">
	        						<input type="number" name="special" class="form-control" min="0" max="99">

	        						<div class="input-group-append">
	        							<span class="input-group-text">%</span>
	        						</div>
	        					</div>
	        				</div>

	        				<div class="form-group">
	        					<label for="weekly">{{ __('messages.discount_for_one_week_rental') }}</label>

	        					<div class="input-group">
	        						<input type="number" name="week" class="form-control" min="0" max="99">

	        						<div class="input-group-append">
	        							<span class="input-group-text">%</span>
	        						</div>
	        					</div>
	        				</div>

	        				<div class="form-group">
	        					<label for="monthly">{{ __('messages.discount_for_one_month_rental') }}</label>

	        					<div class="input-group">
	        						<input type="number" name="month" class="form-control" min="0" max="99">
	        						
	        						<div class="input-group-append">
	        							<span class="input-group-text">%</span>
	        						</div>
	        					</div>
	        				</div>

	        				<div class="form-group">
	        					<input type="submit" class="btn btn-primary" value=" {{ __('messages.save') }} ">
	        				</div>
	        			</form>
        			</div>        			
        		</div>
        	</div>

        	<div class="col-12 col-md-12 mb-5">
        		<div class="card">
        			<div class="card-header">
        				{{ __('messages.properties') }}
        			</div>

        			<div class="card-body">
        				@foreach($properties as $property)
	        				<div class="badge badge-secondary" style="background-color: {{ $property->color }}">
	        					{{ $property->id }}. {{ $property->name }}
	        				</div>
	            		@endforeach
        			</div>        			
        		</div>
        	</div>
		</div>
	</div>

	<input type="hidden" name="pricesByDates" value='{!! json_encode($data) !!}'>

	@if(request()->property_id)
		<input type="hidden" name="specialByDates" value='{!! json_encode($special ?? []) !!}'>
	@endif
@stop    

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

	<script>
		selected = [];

		@if(! request()->price)
			$('.show-min-max-smart-price').hide();
		@endif

		document.addEventListener('DOMContentLoaded', function() {
	        var element = document.getElementById('calendar');
	        
	        var calendar = new FullCalendar.Calendar(element, {
	        	initialView: 'dayGridMonth',
	        	events: {!! $events !!},
	        	eventContent: function(info) {
	        		return {
	        			html: info.event.title
	        		};
		      	},
		      	dayCellContent: function (date) {
		      		blocked = {!! $blocked !!};

		      		@if(request()->property_id && request()->price)
			      		prices = $('[name=pricesByDates]').val();
			      		prices = JSON.parse(prices);

			      		special = $('[name=specialByDates]').val();
			      		special = JSON.parse(special);

			      		dateFormat = date.date.getUTCFullYear() + '-' + ('0' + (date.date.getUTCMonth()+1)).slice(-2) + '-' + ('0' + date.date.getUTCDate()).slice(-2);
			      		current = (new Date()).getUTCFullYear() + '-' + ('0' + ((new Date()).getUTCMonth()+1)).slice(-2) + '-' + ('0' + (new Date()).getUTCDate()).slice(-2);

			      		if (dateFormat > current) {
				      		for (var i = blocked.length - 1; i >= 0; i--) {
			        			if (blocked[i].date == dateFormat) {
			        				string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br>Bs. ' + {{ request()->price }} + '.00';

			        				if (special[dateFormat]) {
			        					priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
			        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
			        				}

			        				return {
					        			html: string
					        		};
			        			}
			        		}

			        		string = '<b>' + date.dayNumberText + '</b><br><br>Bs. ' + {{ request()->price }} + '.00';

			        		if (special[dateFormat]) {
			        			priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
	        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
	        				}

			        		return {
			        			html: string
			        		};	      			
			      		} else {
			      			for (var i = blocked.length - 1; i >= 0; i--) {
			        			if (blocked[i].date == dateFormat) {
			        				string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br>Bs. ' + prices[dateFormat] + '.00';

			        				if (special[dateFormat]) {
			        					priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
			        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
			        				}

			        				return {
					        			html: string
					        		};
			        			}
			        		}

			        		string = '<b>' + date.dayNumberText + '</b><br><br>Bs. ' + prices[dateFormat] + '.00';

			        		if (special[dateFormat]) {
			        			priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
	        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
	        				}

			        		return {
			        			html: string
			        		};
			      		}

		        	@elseif(request()->property_id)
			      		prices = $('[name=pricesByDates]').val();
			      		prices = JSON.parse(prices);

			      		special = $('[name=specialByDates]').val();
			      		special = JSON.parse(special);


			      		dateFormat = date.date.getUTCFullYear() + '-' + ('0' + (date.date.getUTCMonth()+1)).slice(-2) + '-' + ('0' + date.date.getUTCDate()).slice(-2);

			      		for (var i = blocked.length - 1; i >= 0; i--) {
		        			if (blocked[i].date == dateFormat) {
		        				string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br>Bs. ' + prices[dateFormat] + '.00';

		        				if (special[dateFormat]) {
		        					priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
		        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
		        				}

		        				return {
				        			html: string
				        		};
		        			}
		        		}

		        		string = '<b>' + date.dayNumberText + '</b><br><br>Bs. ' + prices[dateFormat] + '.00';

		        		if (special[dateFormat]) {
		        			priceSpecial = prices[dateFormat] - (prices[dateFormat] * special[dateFormat] / 100);
        					string = '<b><strike>' + date.dayNumberText + '</strike></b><br><br><strike>Bs. ' + prices[dateFormat] + '.00</strike><br><br>Bs. ' + priceSpecial;
        				}

		        		return {
		        			html: string
		        		};
		        	@else
		        		dateFormat = date.date.getUTCFullYear() + '-' + ('0' + (date.date.getUTCMonth()+1)).slice(-2) + '-' + ('0' + date.date.getUTCDate()).slice(-2);

		        		for (var i = blocked.length - 1; i >= 0; i--) {
		        			if (blocked[i].date == dateFormat) {
		        				return {
				        			html: '<b><strike>' + date.dayNumberText + '</strike></b>'
				        		};
		        			}
		        		}

		        		return {
		        			html: '<b>' + date.dayNumberText + '</b>'
		        		};
		        	@endif
		      	},
	        	eventClick: function (info) {
	        		window.open('/booking/' + info.event._def.publicId, '_blank');
	        	},
	        	dateClick: function (info) {
	        		events = {!! $events !!};
	        		blocked = {!! $blocked !!};

	        		for (var i = blocked.length - 1; i >= 0; i--) {
	        			if (blocked[i].date == info.dateStr) {
	        				unlock = confirm('¿Desea desbloquear este fecha?');

	        				if (unlock) {
	        					window.location.href = '/unlock?date=' + info.dateStr;
	        				}
	        			}
	        		}

	        		@if(request()->property_id)
	        			special = $('[name=specialByDates]').val();
			      		special = JSON.parse(special);

			      		if (special[info.dateStr]) {
	        				deleteSpecial = confirm('¿Desea eliminar el descuento especial?');

	        				if (deleteSpecial) {
	        					window.location.href = '/property-price-date/delete?date=' + info.dateStr + '&property_id={{ request()->property_id }}';
	        				}

	        				return;
	        			}
	        		@endif

	        		found = 0;

	        		for (var i = events.length - 1; i >= 0; i--) {
	        			if (events[i].start == info.dateStr) {
	        				found = 1;
	        			}
	        		}

	        		for (var i = blocked.length - 1; i >= 0; i--) {
	        			if (blocked[i].date == info.dateStr) {
	        				return;
	        			}
	        		}

	        		if (! found) {
		        		if (selected.indexOf(info.dateStr) != -1) {
		        			i = selected.indexOf(info.dateStr);
		        			selected.splice(i, 1);
		        			$(info.dayEl).css('background-color', 'white');
		        			$('[name=date]').val('');

		        		} else {
		        			selected.push(info.dateStr);
			        		$(info.dayEl).css('background-color', '#e0eded');
			        		$('[name=date]').val(info.dateStr);
		        		}	        			
	        		}

	        		if (selected.length) {
	        			string = JSON.stringify(selected);
	        			$('.input-block-selected-dates').val(string);
	        			$('.btn-block-selected-dates').show();
	        		} else {
	        			$('.input-block-selected-dates').val('');
	        			$('.btn-block-selected-dates').hide();
	        		}

	        		property_id = $('[name=property_id]').val();

	        		if (property_id) {
		        		$.ajax({
		        			type: 'GET',
		        			url: '/property-price-date',
		        			data: {
		        				date: info.dateStr,
		        				property_id: property_id
		        			},
		        			success: function (response) {
		        				if (response) {
		        					$('[name=price]').val(response);
		        				} else {
		        					if (property_id) {
			        					price = $('[name=property_id]').find(':selected').attr('data-price');
			    						$('[name=price]').val(price);
			    					}
		        				}
		        			},
		        			error: function (error) {
		        				$('body').html(error.responseText);
		        			}
		        		});
	        		}
	        	}
	        });

	        calendar.render();
	    });

	    $(document).ready(function () {
	    	$('.btn-block-selected-dates').hide();

	    	$('.btn-block-selected-dates').click(function () {
	    		dates = $('.input-block-selected-dates').val();

	    		$.ajax({
	    			url: '/blocked-dates',
	    			data: {
	    				dates: dates,
	    				_token: '{{ csrf_token() }}'
	    			},
	    			type: 'POST',
	    			success: function (response) {
	    				window.location.href = window.location.href;
	    			},
	    			error: function (error) {
	    				$('body').html(error.responseText);
	    			}
	    		});
	    	});

	    	$('[name=property_id]').change(function () {
	    		date = $('[name=date]').val();
	    		property_id = $('[name=property_id]').val();

	    		$.ajax({
        			type: 'GET',
        			url: '/property-price-date',
        			data: {
        				date: date,
        				property_id: property_id
        			},
        			success: function (response) {
        				if (response) {
        					$('[name=price]').val(response);
        				} else {
        					price = $('[name=property_id]').find(':selected').attr('data-price');
		    				$('[name=price]').val(price);
        				}

        				week = $('[name=property_id]').find(':selected').attr('data-week');
        				$('[name=week]').val(week);

        				month = $('[name=property_id]').find(':selected').attr('data-month');
        				$('[name=month]').val(month);
        			},
        			error: function (error) {
        				$('body').html(error.responseText);
        			}
        		});
	    	});

	    	$('[name=date]').change(function () {
	    		date = $('[name=date]').val();
	    		property_id = $('[name=property_id]').val();

	    		if (property_id) {
		    		$.ajax({
	        			type: 'GET',
	        			url: '/property-price-date',
	        			data: {
	        				date: date,
	        				property_id: property_id
	        			},
	        			success: function (response) {
	        				if (response) {
	        					$('[name=price]').val(response);
	        				} else {
	        					price = $('[name=property_id]').find(':selected').attr('data-price');
			    				$('[name=price]').val(price);
	        				}
	        			},
	        			error: function (error) {
	        				$('body').html(error.responseText);
	        			}
	        		});	    			
	    		}
	    	});

	    	$('[name=smart_price]').click(function () {
	    		$('.show-min-max-smart-price').hide();

	    		if ($(this).is(':checked')) {
	    			$('.show-min-max-smart-price').show();

	    			property_id = $('[name=property_id]').val();
	    			min = $('[name=smart_price_min]').val();
	    			max = $('[name=smart_price_max]').val();

	    			if (property_id && min && max) {
		    			$.ajax({
		    				type: 'GET',
		    				url: '/smart-price',
		    				data: {
		    					property_id: property_id,
		    					min: min,
		    					max: max,
		    				},
		    				success: function (response) {
		    					console.log(response);

		    					if (response) {
		    						window.location.href = '?min=' + min + '&max=' + max + '&property_id={{ request()->property_id }}&price=' + response;
		    					}
		    				},
		    				error: function (error) {
		    					console.log(error);
		    				}
		    			});	    				
	    			}
	    		} else {
	    			window.location.href = '?property_id={{ request()->property_id }}';
	    		}
	    	});

	    	$('[name=smart_price_min]').keyup(function () {
	    		property_id = $('[name=property_id]').val();
    			min = $('[name=smart_price_min]').val();
    			max = $('[name=smart_price_max]').val();

    			if (property_id && min && max) {
	    			$.ajax({
	    				type: 'GET',
	    				url: '/smart-price',
	    				data: {
	    					property_id: property_id,
	    					min: min,
	    					max: max,
	    				},
	    				success: function (response) {
	    					console.log(response);
		    					
	    					if (response) {
	    						window.location.href = '?min=' + min + '&max=' + max + '&property_id={{ request()->property_id }}&price=' + response;
	    					}
	    				},
	    				error: function (error) {
	    					console.log(error);
	    				}
	    			});	    				
    			}
	    	});

	    	$('[name=smart_price_max]').keyup(function () {
	    		property_id = $('[name=property_id]').val();
    			min = $('[name=smart_price_min]').val();
    			max = $('[name=smart_price_max]').val();

    			if (property_id && min && max) {
	    			$.ajax({
	    				type: 'GET',
	    				url: '/smart-price',
	    				data: {
	    					property_id: property_id,
	    					min: min,
	    					max: max,
	    				},
	    				success: function (response) {
	    					console.log(response);
		    					
	    					if (response) {
	    						window.location.href = '?min=' + min + '&max=' + max + '&property_id={{ request()->property_id }}&price=' + response;
	    					}
	    				},
	    				error: function (error) {
	    					console.log(error);
	    				}
	    			});	    				
    			}
	    	});
	    });
	</script>
@endpush