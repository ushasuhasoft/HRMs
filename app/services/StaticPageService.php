<?php
class StaticPageService
{
	public function getPageDetails($pg_name)
	{
		return StaticPageMdl::where('page_name', $pg_name)->where('is_deleted', 0)->first();
	}
}
