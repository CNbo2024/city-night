<aside class="main-sidebar">
    <section class="sidebar">
	<ul class="sidebar-menu">
        <li class="{{ (Route::current()->uri() == 'admin/dashboard') ? 'active' : ''  }}"><a href="{{ url('admin/dashboard') }}"><i class="fa fa-desktop"></i><span>Dashboard</span></a></li>
        <li class="{{ (Route::current()->uri() == 'admin/tips') ? 'active' : ''  }}"><a href="{{ url('admin/tips') }}"><i class="fa fa-info"></i><span>Tips</span></a></li>
        <li class="{{ (Route::current()->uri() == 'admin/identify-verification') ? 'active' : ''  }}"><a href="{{ url('admin/identify-verification') }}"><i class="fa fa-check"></i><span>Identify verification</span></a></li>
        <li class="{{ (Route::current()->uri() == 'admin/promotion') ? 'active' : ''  }}"><a href="{{ url('admin/promotion') }}"><i class="fa fa-gift"></i><span>Promotion</span></a></li>
        
        <li class="treeview {{ (Route::current()->uri() == 'admin/settings' || Route::current()->uri() == 'admin/settings/preferences') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-cog"></i> <span>Site Settings</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Permission::has_permission(Auth::guard('admin')->user()->id, 'general_setting'))
    				<li class="{{ (Route::current()->uri() == 'admin/settings') ? 'active' : ''  }}">
    					<a href="{{ url('admin/settings') }}" data-group="profile">General</a>
    				</li>
			        @endif
			        @if(Permission::has_permission(Auth::guard('admin')->user()->id, 'preference'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/preferences') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/preferences') }}" data-group="profile">Preferences</a>
        				</li>
        			@endif
				</ul>
			</li>
			@if(config('global.demosite')=="no")

			 <li class="treeview {{ (Route::current()->uri() == 'admin/admin-users' || Route::current()->uri() == 'admin/settings/roles') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-user-secret"></i> <span>Manage Admins</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_admin'))
            			<li class="{{ (Route::current()->uri() == 'admin/admin-users') || (Route::current()->uri() == 'admin/add-admin') || (Route::current()->uri() == 'admin/edit-admin/{id}') ? 'active' : ''  }}">
            				<a href="{{ url('admin/admin-users') }}"><span>Admin Users</span></a>
            			</li>
		            @endif
		            @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_roles'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/roles' || Route::current()->uri() == 'admin/permissions' || Route::current()->uri() == 'admin/settings/add-role' || Route::current()->uri() == 'admin/settings/edit-role/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/roles') }}"><span>Roles & Permissions</span></a>
        				</li>
			        @endif
				</ul>
			</li>
		@endif
			
		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'customers'))
			<li class="{{ (Route::current()->uri() == 'admin/customers') || (Route::current()->uri() == 'admin/add-customer') || (Route::current()->uri() == 'admin/edit-customer/{id}') || (Route::current()->uri() == 'admin/customer/properties/{id}')  || (Route::current()->uri() == 'admin/customer/bookings/{id}') || (Route::current()->uri() == 'admin/customer/payouts/{id}')  || (Route::current()->uri() == 'admin/customer/payment-methods/{id}') || (Route::current()->uri() == 'admin/customer/wallet/{id}')  ? 'active' : '' }} }}"><a href="{{ url('admin/customers') }}"><i class="fa fa-users"></i><span>Manage Users</span></a></li>
		@endif
		
		
		 <li class="treeview {{ (Route::current()->uri() == 'admin/document_verification' || Route::current()->uri() == 'admin/approve_document_verification') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-user-secret"></i> <span>Document Verification</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
            			<li class="{{ (Route::current()->uri() == 'admin/document_verification') ? 'active' : ''  }}">
            				<a href="{{ url('admin/document_verification') }}"><span>Disapprove Documents</span></a>
            			</li>
        				<li class="{{ (Route::current()->uri() == 'admin/approve_document_verification')  ? 'active' : ''  }}">
        					<a href="{{ url('admin/approve_document_verification') }}"><span>Approve Document</span></a>
        				</li>
				</ul>
			</li>
		
        <!--<li class="{{ (Route::current()->uri() == 'admin/document_verification') ? 'active' : ''  }}"><a href="{{ url('admin/document_verification') }}"><i class="fa fa-file-text-o"></i><span>Document Verification</span></a></li>-->


		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'properties'))
			<li class="{{ (Route::current()->uri() == 'admin/properties') || (Route::current()->uri() == 'admin/add-properties') || (Route::current()->uri() == 'admin/listing/{id}/{step}') ? 'active' : ''  }}"><a href="{{ url('admin/properties') }}"><i class="fa fa-list"></i><span>Manage Listings</span></a></li>
		@endif
		
		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'experience'))
			<li class="{{ (Route::current()->uri() == 'admin/experience') || (Route::current()->uri() == 'admin/add-experience') || (Route::current()->uri() == 'admin/experience/{id}/{step}') ? 'active' : ''  }}"><a href="{{ url('admin/experience') }}"><i class="fa fa-list"></i><span>Manage Experience</span></a></li>
		@endif
		
		<li class="treeview {{ (Route::current()->uri() == 'admin/experience/experience_category' || Route::current()->uri() == 'admin/experience/add_experience_category' || Route::current()->uri() == 'admin/experience/edit_experience_category/{id}' || Route::current()->uri() == 'admin/experience/inclusion' || Route::current()->uri() == 'admin/experience/add_inclusion' || Route::current()->uri() == 'admin/experience/edit_inclusion/{id}' || Route::current()->uri() == 'admin/experience/exclusion' || Route::current()->uri() == 'admin/experience/add_exclusion' || Route::current()->uri() == 'admin/experience/edit_exclusion/{id}' ) ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-sliders"></i> <span>Experience Settings</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_experience_category'))
        				<li class="{{ (Route::current()->uri() == 'admin/experience/experience_category' || Route::current()->uri() == 'admin/experience/add_experience_category' || Route::current()->uri() == 'admin/experience/edit_experience_category/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/experience/experience_category') }}" data-group="property_type">Experience Category</a>
        				</li>
			        @endif
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_inclusion'))
        				<li class="{{ (Route::current()->uri() == 'admin/experience/inclusion' || Route::current()->uri() == 'admin/experience/add_inclusion' || Route::current()->uri() == 'admin/experience/edit_inclusion/{id}' ) ? 'active' : ''  }}">
        					<a href="{{ url('admin/experience/inclusion') }}" >Inclusion</a>
        				</li>
			        @endif
					
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_exclusion'))
        				<li class="{{ (Route::current()->uri() == 'admin/experience/exclusion' || Route::current()->uri() == 'admin/experience/add_exclusion' || Route::current()->uri() == 'admin/experience/edit_exclusion/{id}' ) ? 'active' : ''  }}">
        					<a href="{{ url('admin/experience/exclusion') }}" >Exclusion</a>
        				</li>
			        @endif
				</ul>
		</li>
		

		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_bookings'))
			<li class="{{ (Route::current()->uri() == 'admin/bookings') || (Route::current()->uri() == 'admin/bookings/detail/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/bookings') }}"><i class="fa fa-calendar-check-o"></i><span>Manage Bookings</span></a></li>
		@endif

		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'view_payouts'))
			<li class="{{ (Route::current()->uri() == 'admin/payouts') || (Route::current()->uri() == 'admin/payouts/details/{id}') || (Route::current()->uri() == 'admin/payouts/edit/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/payouts') }}"><i class="fa fa-credit-card-alt"></i><span>Payout Requests</span></a></li>
		@endif
		
          <li class="treeview {{ (Route::current()->uri() == 'admin/guest_penalty' || Route::current()->uri() == 'admin/host_penalty') ? 'active' : ''  }}">
            <a href="#">
              <i class="fa fa-plane"></i> <span>Penalty</span><i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li class="{{ (Route::current()->uri() == 'admin/host_penalty') ? 'active' : ''  }}"><a href="{{ url('admin/host_penalty') }}"><span>Host Penalty</span></a></li>
            </ul>
          </li>
        
		
		<li class="treeview {{ (Route::current()->uri() == 'admin/settings/property-type' || Route::current()->uri() == 'admin/settings/space-type' || Route::current()->uri() == 'admin/settings/bed-type') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-sliders"></i> <span>Listing Settings</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_property_type'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/property-type' || Route::current()->uri() == 'admin/settings/add-property-type' || Route::current()->uri() == 'admin/settings/edit-property-type/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/property-type') }}" data-group="property_type">Property Type</a>
        				</li>
			        @endif
			        @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'space_type_setting'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/space-type' || Route::current()->uri() == 'admin/settings/add-space-type' || Route::current()->uri() == 'admin/settings/edit-space-type/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/space-type') }}" data-group="space_type">Space Type</a>
        				</li>
			        @endif
    				@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_bed_type'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/bed-type' || Route::current()->uri() == 'admin/settings/add-bed-type'|| Route::current()->uri() == 'admin/settings/edit-bed-type/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/bed-type') }}" data-group="bed_type">Bed Type</a>
        				</li>
			        @endif
				</ul>
			</li>
			<li class="treeview {{ (Route::current()->uri() == 'admin/amenities' || Route::current()->uri() == 'admin/settings/amenities-type') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-check-circle-o"></i> <span>Manage Amenities</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_amenities'))
            			<li class="{{ (Route::current()->uri() == 'admin/amenities') || (Route::current()->uri() == 'admin/add-amenities') || (Route::current()->uri() == 'admin/edit-amenities/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/amenities') }}"><span>All Amenities</span></a></li>
		            @endif
		            @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_amenities_type'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/amenities-type' || Route::current()->uri() == 'admin/settings/add-amenities-type' || Route::current()->uri() == 'admin/settings/edit-amenities-type/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/amenities-type') }}">Amenities Type</a>
        				</li>
			        @endif
				</ul>
			</li>
		
            @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_reviews'))
			    <li class="{{ (Route::current()->uri() == 'admin/reviews') || (Route::current()->uri() == 'admin/edit_review/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/reviews') }}"><i class="fa fa-commenting-o"></i><span>User Reviews</span></a></li>
		    @endif
		
			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_testimonial'))
			    <li class="{{ (Route::current()->uri() == 'admin/testimonials') || (Route::current()->uri() == 'admin/edit-testimonials/{id}') || (Route::current()->uri() == 'admin/add-testimonials') ? 'active' : ''  }}"><a href="{{ url('admin/testimonials') }}"><i class="fa fa-list-alt" aria-hidden="true"></i><span>Site Testimonials</span></a></li>
		    @endif
		    
	    	@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_messages'))
    			<li class="{{ (Route::current()->uri() == 'admin/messages') || (Route::current()->uri() == 'admin/messaging/host/{id}') || (Route::current()->uri() == 'admin/send-message-email/{id}') ? 'active' : ''  }}">
    				<a href="{{ url('admin/messages') }}">
    				<i class="fa fa-comments-o"></i> <span>Customer Messages</span>
    				</a>
    			</li>
		    @endif
		    
	    
			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'view_reports'))
			<li class="treeview {{ (Route::current()->uri() == 'admin/sales-report' || Route::current()->uri() == 'admin/sales-analysis' || Route::current()->uri() == 'admin/overview-stats' || Route::current()->uri() == 'admin/overview-stats-experience' || Route::current()->uri() == 'admin/sales-analysis-experience') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-area-chart "></i> <span>Reports & Stats</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
				    <li class="treeview {{ (Route::current()->uri() == 'admin/overview-stats' || Route::current()->uri() == 'admin/overview-stats-experience') ? 'active' : ''  }}">
        				<a href="#">
        			         <span>Overview</span><i class="fa fa-angle-left pull-right"></i>
        				</a> 
        				<ul class="treeview-menu">
        					<li class="{{ (Route::current()->uri() == 'admin/overview-stats') ? 'active' : ''  }}"><a href="{{ url('admin/overview-stats') }}"><span>Overview (Property)</span></a></li>
				            <li class="{{ (Route::current()->uri() == 'admin/overview-stats-experience') ? 'active' : ''  }}"><a href="{{ url('admin/overview-stats-experience') }}"><span>Overview (Experience)</span></a></li>
        				</ul>
			        </li> 
				    <li class="{{ (Route::current()->uri() == 'admin/sales-report') ? 'active' : ''  }}"><a href="{{ url('admin/sales-report') }}"><span>Booking Report</span></a></li>
				
        			<li class="treeview {{ (Route::current()->uri() == 'admin/sales-analysis' || Route::current()->uri() == 'admin/sales-analysis-experience') ? 'active' : ''  }}">
            				<a href="#">
            			         <span>Data Analysis</span><i class="fa fa-angle-left pull-right"></i>
            				</a>
            				<ul class="treeview-menu">
            					<li class="{{ (Route::current()->uri() == 'admin/sales-analysis') ? 'active' : ''  }}"><a href="{{ url('admin/sales-analysis') }}"><span>Data Analysis Property</span></a></li>
            				    <li class="{{ (Route::current()->uri() == 'admin/sales-analysis-experience') ? 'active' : ''  }}"><a href="{{ url('admin/sales-analysis-experience') }}"><span>Data Analysis Experience</span></a></li>
            
            				</ul>
        		    </li>
            </ul>
			</li>
		@endif
		
		<li class="treeview {{ (Route::current()->uri() == 'admin/settings/banners' || Route::current()->uri() == 'admin/settings/starting-cities') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-home"></i> <span>Home Page Settings</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Permission::has_permission(Auth::guard('admin')->user()->id, 'manage_banners'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/banners') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/banners') }}" data-group="profile">Banners</a>
        				</li>
			        @endif
			        @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'starting_cities_settings'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/starting-cities') || (Route::current()->uri() == 'admin/settings/add-starting_cities') || (Route::current()->uri() == 'admin/settings/edit-starting-cities/{id}') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/starting-cities') }}" data-group="home_cities">Popular Cities</a>
        				</li>
			        @endif
				</ul>
			</li>
			
			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_pages'))
			    <li class="{{ (Route::current()->uri() == 'admin/pages') || (Route::current()->uri() == 'admin/add-page') || (Route::current()->uri() == 'admin/edit-page/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/pages') }}"><i class="fa fa-newspaper-o"></i><span>Static Page CMS</span></a></li>
		    @endif
		
		
		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_country'))
			<li class="{{ (Route::current()->uri() == 'admin/settings/country' || Route::current()->uri() == 'admin/settings/add-country' || Route::current()->uri() == 'admin/settings/edit-country/{id}') ? 'active' : ''  }}">
				<a href="{{ url('admin/settings/country') }}"><i class="fa fa-globe"></i><span>Manage Country</span></a>
			</li>
		@endif
		@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_currency'))
			<li class="{{ (Route::current()->uri() == 'admin/settings/currency' || Route::current()->uri() == 'admin/settings/add-currency' || Route::current()->uri() == 'admin/settings/edit-currency/{id}') ? 'active' : ''  }}">
				<a href="{{ url('admin/settings/currency') }}" data-group="currency"><i class="fa fa-money"></i><span>Manage Currency</span></a>
			</li>
		@endif
			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_language'))
				<li class="{{ (Route::current()->uri() == 'admin/settings/language' || Route::current()->uri() == 'admin/settings/add-language' || Route::current()->uri() == 'admin/settings/edit-language/{id}') ? 'active' : ''  }}">
					<a href="{{ url('admin/settings/language') }}" data-group="language"><i class="fa fa-language"></i><span>Manage Languages</span></a>
				</li>
			@endif
			
			
			<li class="treeview {{ (Route::current()->uri() == 'admin/settings/payment-methods' || Route::current()->uri() == 'admin/settings/fees') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-university"></i> <span>Payment & Fee settings</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'payment_settings'))
    				<li class="{{ (Route::current()->uri() == 'admin/settings/payment-methods') ? 'active' : ''  }}">
    					<a href="{{ url('admin/settings/payment-methods') }}" data-group="payment_methods">Payment Methods</a>
    				</li>
	        @endif
	        @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_fees'))
    				<li class="{{ (Route::current()->uri() == 'admin/settings/fees') ? 'active' : ''  }}">
    					<a href="{{ url('admin/settings/fees') }}">Fees & Tax Settings</a>
    				</li>
	        @endif
	        <li class="{{ (Route::current()->uri() == 'admin/settings/min-request') ? 'active' : ''  }}">
  					<a href="{{ url('admin/min-request') }}" data-group="min_request">Min amount for payout</a>
  				</li>
				</ul>
			</li>
			<li class="treeview {{ (Route::current()->uri() == 'admin/settings/email' || Route::current()->uri() == 'admin/email-template/{id}') ? 'active' : ''  }}">
				<a href="#">
				<i class="fa fa-envelope-o"></i> <span>Manage Emails</span><i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'email_settings'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/email') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/email') }}">Email Settings</a>
        				</li>
        			@endif
			        @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_email_template'))
			            <li class="{{ (Route::current()->uri() == 'admin/email-template/{id}') ? 'active' : ''  }}"><a href="{{ url('admin/email-template/1') }}"><span>Email Templates</span></a></li>
            		@endif
				</ul>
			</li>
			@if(Permission::has_permission(Auth::guard('admin')->user()->id, 'manage_sms'))
				<li class="{{ (Route::current()->uri() == 'admin/settings/sms') ? 'active' : ''  }}">
						<a href="{{ url('admin/settings/sms') }}" data-group="sms"><i class="fa fa-mobile"></i> <span>SMS Settings</span></a>
				</li>
			@endif
			<li class="treeview {{ (Route::current()->uri() == 'admin/settings/social-links' || Route::current()->uri() == 'admin/settings/api-informations') ? 'active' : ''  }}">
    			<a href="#">
    			<i class="fa fa-facebook-square"></i> <span>Social Page & login Settings</span><i class="fa fa-angle-left pull-right"></i>
    			</a>
    			<ul class="treeview-menu">
					@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'social_links'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/social-links') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/social-links') }}" data-group="social_links">Social Media Links</a>
        				</li>
			        @endif
    		        @if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'api_informations'))
        				<li class="{{ (Route::current()->uri() == 'admin/settings/api-informations') ? 'active' : ''  }}">
        					<a href="{{ url('admin/settings/api-informations') }}" data-group="api_informations">Social Login API</a>
        				</li>
			        @endif
    			</ul>
			</li>
			
			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'manage_metas'))
				<li class="{{ (Route::current()->uri() == 'admin/settings/metas' || Route::current()->uri() == 'admin/settings/edit_meta/{id}') ? 'active' : ''  }}">
					<a href="{{ url('admin/settings/metas') }}" data-group="metas"><i class="fa fa-search-plus"></i> <span>SEO Settings</span></a>
				</li>
			@endif
			
			@if(config('global.demosite')=="no")

    			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'database_backup'))
    			<li class="{{ (Route::current()->uri() == 'admin/settings/backup') ? 'active' : ''  }}">
    				<a href="{{ url('admin/settings/backup') }}"><i class="fa fa-database"></i> <span>Site Database Backup</span></a>
    			</li>
    			@endif
			@endif
			
			@if(config('global.demosite')=="no")
    			@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'delete_demo_content'))
        			<li class="{{ (Route::current()->uri() == 'admin/settings/delete-demo') ? 'active' : ''  }}">
        				<a href="{{ url('admin/settings/delete-demo') }}" data-group="delete-demo"><i class="fa fa-trash"></i> <span>Delete Content</span></a>
        			</li>
    			@endif
			@endif

		<!-- Email Template Ends -->  
	<!--	@if(Helpers::has_permission(Auth::guard('admin')->user()->id, 'general_setting'))
			<li class="{{ (Request::segment(2) == 'settings') ? 'active' : ''  }}"><a href="{{ url('admin/settings') }}"><i class="fa fa-gears"></i><span>Settings</span></a></li>
		@endif-->
    </ul>
    </section>
</aside>