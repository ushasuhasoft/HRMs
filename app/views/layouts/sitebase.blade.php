<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $header->getMetaTitle() }}</title>
<!-- STYLESHEETS -->
<link href="{{ URL::asset('css/bootstrap-3.2.0.min.css') }}" rel="stylesheet"/> <!-- changed by vasanthi to bootstrap 3 -->
<!-- <link href="{{ URL::asset('css/bootstrap-responsive.min.css') }}" rel="stylesheet"/>  -->
<link href="{{ URL::asset('css/uniform.tp.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/colorpicker.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/colorbox.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/jquery.jgrowl.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/jquery.alerts.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/animate.min.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/animate.delay.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/font-awesome.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/jquery.tagsinput.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/ui.spinner.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/jquery.chosen.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/ui-lightness/jquery-ui-1.10.3.custom.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/fullcalendar.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/font-awesome-ie7.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('fonts/roboto.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/style.default.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('prettify/prettify.css') }}" rel="stylesheet"/>
<!-- added by vasanthi -->
<link href="{{ URL::asset('css/jquery.timepicker.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/bootstrap-multiselect.css') }}" rel="stylesheet"/>
<!-- added by vasanthi -->
<!-- END STYLESHEETS -->

<!- Javascript -->
<script src="{{ URL::asset('prettify/prettify.js') }}"></script>
<script src="{{ URL::asset('js/jquery-1.8.3.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery-ui-1.9.2.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-3.2.0.min.js') }}"></script> <!-- changed by vasanthi to bootstrap 3 -->
<script src="{{ URL::asset('js/bootbox_new.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-multiselect.js') }}"></script>
<script src="{{ URL::asset('js/jquery.uniform.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.validate.js') }}"></script>
<script src="{{ URL::asset('js/jquery.tagsinput.min.js') }}"></script>
<script src="{{ URL::asset('js/charCount.js') }}"></script>
<script src="{{ URL::asset('js/ui.spinner.min.js') }}"></script>
<script src="{{ URL::asset('js/chosen.jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.timepicker.min.js') }}"></script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
<!-- <script src="{{ URL::asset('js/forms.js') }}"></script> -->
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<!- END Javascript -->
<!-- <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'> -->
</head>
<body>
<div class="mainwrapper fullwrapper" style="background-position: 0px 0px;">
<!-- START OF LEFT PANEL -->
    <div class="leftpanel">    	
        <div class="logopanel">
        	<h1><a href="dashboard.html"><img src={{ URL::asset("img/logonew.png") }}></a></h1>
        </div><!--logopanel-->
        
        <div class="datewidget">Today is {{ date('l, M j Y ') }} </div>
    
    	
		 <div class="leftmenu">
			@include('site.leftMenu')
		</div><!--leftmenu-->        
    </div><!--mainleft-->
    <!-- END OF LEFT PANEL -->
	<!-- START OF RIGHT PANEL -->
	 <div class="rightpanel">
    	<div class="headerpanel">
        	<a href="#" class="showmenu"></a>
            
            <div class="headerright">
            	<!--dropdown-->
                
    			<div class="dropdown userinfo">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">Hi, {{ (isLoggedin() ? getAuthUser()->user_name : 'Guest') }}! <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <!--  <li><a href="#"><span class="icon-edit"></span> Edit Profile</a></li>
                        <li><a href="#"><span class="icon-wrench"></span> Account Settings</a></li>
                        <li><a href="#"><span class="icon-eye-open"></span> Privacy Settings</a></li>
                        <li class="divider"></li> -->
                        <li><a href="{{ URL::to('user/logout') }}"><span class="icon-off"></span> Sign Out</a></li>
                    </ul>
                </div><!--dropdown-->
    		
            </div><!--headerright-->
            
    	</div><!--headerpanel-->
        <div class="breadcrumbwidget">          
        	<ul class="breadcrumb">
                <li><a href="dashboard.html">Home</a> <span class="divider">/</span></li>
                @yield('breadcrumb')
            </ul>
        </div><!--breadcrumbs-->
      <div class="pagetitle">
        	<h1>{{ $header->getPageTitle() }}</h1> <span></span>
      </div><!--pagetitle-->


      @if($header->isProfilePage())
       <div class="maincontent">
              	<div class="contentinner content-editprofile">
                  	<h4 class="widgettitle nomargin">Edit Profile</h4>
                      <div class="widgetcontent bordered">
                        @include('site.notification')
                      	<div class="row">
                               @yield('content')
                        </div><!--row-fluid-->
                      </div><!--widgetcontent-->
                </div><!--contentinner--><!--contentinner-->
       </div><!--maincontent-->
      @else
	  <div class="maincontent">
      	<div class="contentinner content-dashboard">            	                
          <div class="row-fluid">
                	<!--span8-->
                	@include('site.notification')
					@yield('content')
		  </div><!--row-fluid-->
        </div><!--contentinner-->        
            
      </div><!--maincontent-->
      @endif
        
  </div><!--mainright-->
  <!-- END OF RIGHT PANEL -->

<div class="clearfix"></div>
    
    <div class="footer">
    	<div class="footerleft">Spring Solutions Inc</div>
    	<div class="footerright">&copy; Copyrights 2014</div>
    </div><!--footer-->

    
</div><!--mainwrapper-->
<script>
    var $= jQuery.noConflict();
     $(document).ready(function()
     {
       $(".alert-success").fadeOut(3000 );
     });
</script>
</body>
</html>