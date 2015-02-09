<?php
class StaticPageController extends BaseController
{
	function __construct() {

    }
    public function showContent($pg_name)
    {
		$service = new StaticPageService();
    	$content = $service->getPageDetails($pg_name);
    	if(!$content)
    	{
    		return Redirect::to('/')->with('flash_msg', 'Sorry, the page you are looking for is not found. Please check if you have typed correctly.');
    	}
    	return View::make('staticPage', compact('content'));
	}

}