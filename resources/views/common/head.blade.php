<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="viewport" content="user-scalable=no, width=device-width">
		<link rel="dns-prefetch" href="https://maps.googleapis.com/">
        <link rel="dns-prefetch" href="https://maps.gstatic.com/">
        <link rel="dns-prefetch" href="https://mts0.googleapis.com/">
        <link rel="dns-prefetch" href="https://mts1.googleapis.com/">
		<!-- Metas For sharing property in social media -->
		<meta property="og:url"                content="{{ isset($shareLink) ? url()->current() : url('/') }}" />
		<meta property="og:type"               content="article" />
		<meta property="og:title"              content="{{ isset($title) ? $title : '' }}" />
		
		
		<meta property="og:image"              content="{{ (isset($property_id) && !empty($property_id && isset($property_photos[0]->photo) )) ? url('public/images/property/'.$property_id.'/'.$property_photos[0]->photo) : BANNER_URL  }}" />
		@if (!empty($favicon))
		<link rel="shortcut icon" href="{{ $favicon }}">
		@endif
<?php
			$url	    = Route::getFacadeRoot()->current()->uri();
			$metas      = App\Models\Meta::where('url', $url)->first();
			
			if(isset($result->property_description->summary))
			{
				$meta_desc = $result->property_description->summary;
			}
			else if(isset($metas))
			{
				$meta_desc = $metas->description;
			} 
			else
			{
			    if(isset($title))
			    {
				    $meta_desc = $title;	
			    }
			    else
			    {
			        $meta_desc = "";
			    }
			}

		?>
		<title>{{ $title ?? Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }} {{ $additional_title ?? '' }} </title>
	    <meta name="description" content="{{$meta_desc}}">
	    <meta name="keywords" content="{{ $keywords ?? Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'keywords') }} {{ $additional_keywords ?? '' }}">
		<meta property="og:description" content="{{$meta_desc}}" />	

		<meta name="mobile-web-app-capable" content="yes">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- CSS  new version start-->
		@stack('css')

		<link rel="stylesheet" href="{{asset('public/css/vendors/bootstrap/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('public/css/vendors/fontawesome/css/all.min.css')}}">

		<style>
			@font-face {
			    font-family: Circular;
			    src: url("{{asset('public/front/fonts/manrope.ttf')}}") format("opentype");
			}

			@font-face {
			    font-family: Circular;
			    font-weight: bold;
			    src: url("{{asset('public/front/fonts/manrope.ttf')}}") format("opentype");
			}

			* {
				font-family: 'Circular';
			}
		</style>	

        <?php 
            if(Session::get('language')=="")
            {
                $dlang = $default_language[0]->short_name;
            }
            else
            {
                $dlang = Session::get('language');
            }
            
            $language = App\Models\Language::where('status', '=', 'Active')->where('short_name', $dlang)->first();
            $sv_rtl = $language->enable_rtl;
            if($sv_rtl=="Yes")
            {
        ?>
            <link rel="stylesheet" href="{{asset('public/css/rtl.css')}}">
            <?php    
            }
        ?>
                                        
                                        
		<link rel="stylesheet" href="{{asset('public/css/style.css')}}"> 
		
		<!--CSS new version end-->
	</head>


<body <?php if($homepage_type=="old_home") { ?> style="margin-bottom:0" <?php } ?>>
