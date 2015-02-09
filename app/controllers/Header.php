<?php
class Header{
	public $meta = array();
	public $replace_arr = array();
    public $page_layout_type = '';

	function __construct()
	{
		$current_script = URL::current();
		$current_page = substr($current_script, strrpos($current_script, '/')+1);

		$this->replace_arr = array('VAR_SITE_NAME' => Config::get('site.site_name'));
		$this->meta['title'] =  (Lang::has('meta.'.$current_page.'_title')) ? trans('meta.'.$current_page.'_title'): trans('meta.title');
        $this->meta['keyword'] = (Lang::has('meta.'.$current_page.'_keyword')) ? trans('meta.'.$current_page.'_keyword'): trans('meta.keyword');
        $this->meta['description'] = (Lang::has('meta.'.$current_page.'_description')) ? trans('meta.'.$current_page.'_description'): trans('meta.description');
        $this->meta['page_title'] = '';

    }

    public function getMetaTitle()
    {
		if($this->meta['title'])
			return str_replace(array_keys($this->replace_arr), array_values($this->replace_arr), $this->meta['title']);
    	return $this->meta['title'];
	}

    public function setMetaTitle($value)
    {
    	$this->meta['title'] = $value;
	}
    public function getPageTitle()
    {
        return $this->meta['page_title'];
    }

    public function setpageTitle($value)
    {
        $this->meta['page_title'] = $value;
    }


    public function getMetaKeyword()
    {
    	return $this->meta['keyword'];
	}

    public function setMetaKeyword($value)
    {
    	$this->meta['keyword'] .= $value;
	}

    public function getMetaDescription()
    {
    	return $this->meta['description'];
	}

    public function setMetaDescription($value)
    {
    	$this->meta['description'] .= ' '.$value;
	}

    public function setPageLayoutType($type)
    {
        $this->page_layout_type = $type;
    }
    public function isProfilePage()
    {
        return ($this->page_layout_type == 'profile');
    }

}
