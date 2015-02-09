<?php
class AnnouncementController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->dataService = new AnnouncementService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getAddNews()
    {
        $id = Input::get('id', 0);
        $details = $attachment_details = array();
        if($id)
            $details = $this->dataService->getNewsDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
            $mode = 'add';
        }
        else
        {
            $mode = 'edit';
            $details['date_published'] = fmtAsDisplayDate($details['date_published']);
        }
        if($mode == 'edit')
        {
            $attachment_details =  $this->dataService->listNewsAttachment($this->subscription_id, $id, $attachment_details);
        }
        return View::make('site/announcement/addNews', compact('details', 'attachment_details', 'mode'));
    }

    public function postAddNews()
    {
        $id = Input::get('id', 0);
        $rules = array('topic' => $this->dataService->getEntryValidatorRule('announcement_news', 'topic'));
        $input = Input::All();
        $input['user_id'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $input['status'] = (Input::get('submit_status') == 'draft') ? 'draft' : 'published';
            if($id)
            {
                $this->dataService->updateNews($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addNews($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('announcement/list-news')->with('success_msg', $msg);
        }
    }

    public function getListNews()
    {
        $this->dataService->setSearchFormValues(Input::All());
        $q          = $this->dataService->buildNewsListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 10;
        $details 	= $q->paginate($perPage);
        $dd_arr['status_list'] = $this->dataService->populateNewsStatus();
        return View::make('site/announcement/listNews', compact('details', 'dd_arr'));
    }

    public function postListNews()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        $action = Input::get('action');
        $details    = $this->dataService->updateNewsStatus($this->subscription_id, $checked_ids, $action);
        return Redirect::to('announcement/list-news')->with('success_msg', trans('general.delete_success'));
    }

    public function postDeleteNewsAttachment()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        $news_id = Input::get('news_id', 0);
        $this->dataService->deleteNewsAttachment($this->subscription_id, $news_id, $checked_ids);
        return Redirect::to('announcement/add-news?id='.$news_id)->with('success_msg', trans('general.delete_success'));
    }

    public function getAddNewsAttachment()
    {
        $id = Input::get('id', 0);
        $mode = 'add';
        $details = array();
        if($id)
        {
            $details = $this->dataService->getNewsAttachmentDetailsForEdit($this->subscription_id, $id);
            if(!$details)
            {
                return Redirect::to('announcement/add-news');
            }
            else
            {
                $mode = 'edit';
                $news_id = $details['announcement_news_id'];
            }
        }
        else
        {
            $news_id = Input::get('news_id', 0);
        }
        $dd_arr['max_file_size'] = $this->dataService->getDisplayValidationUnit('announcement_news_attachment_max_file_size');
        if($news_id)
        {
            return View::make('site/announcement/addNewsAttachment',  compact('details', 'dd_arr', 'news_id', 'mode'));
        }
        else
            return Redirect::to('announcement/add-news');

    }
    public function postAddNewsAttachment()
    {
        $id = Input::get('id', 0);
        $news_id = Input::get('news_id');
        $input = Input::All();
        $rules = array();
        $rules['attachment_file'] = '';
        if(!$id)
            $rules['attachment_file'] = 'Required|'.$this->dataService->getEntryValidatorRule('announcement_news', 'attachment_file', $id);
        else
            $rules['attachment_file'] .= $this->dataService->getEntryValidatorRule('announcement_news', 'attachment_file', $id);
        $input['added_by'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if(isset($input['attachment_file']) )
            {
                $file = $input['attachment_file'];
                if($file->getError())
                    return Redirect::back()->withInput()->with('error_msg', 'Sorry errors found, Invalid file size');
            }

            if($id)
            {
                $this->dataService->updateNewsAttachment($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addNewsAttachment($this->subscription_id, $news_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('announcement/add-news?id='.$news_id)->with('success_msg', $msg);
        }
    }

    public function getAddDocument()
    {
        $id = Input::get('id', 0);
        $details = $attachment_details = array();
        if($id)
            $details = $this->dataService->getDocumentDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
            $mode = 'add';
        }
        else
        {
            $mode = 'edit';
            $details['date_published'] = fmtAsDisplayDate($details['date_published']);
        }
        if($mode == 'edit')
        {
            $attachment_details =  $this->dataService->listDocumentAttachment($this->subscription_id, $id, $attachment_details);
        }
        $dd_arr['category_names'] = $this->dataService->populateDocumentCategory($this->subscription_id);
        return View::make('site/announcement/addDocument', compact('details', 'attachment_details', 'mode', 'dd_arr'));
    }

    public function postAddDocument()
    {
        $id = Input::get('id', 0);
        $rules = array('topic' => $this->dataService->getEntryValidatorRule('announcement_document', 'topic'));
        $input = Input::All();
        $input['user_id'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $input['status'] = (Input::get('submit_status') == 'draft') ? 'draft' : 'published';
            if($id)
            {
                $this->dataService->updateDocument($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addDocument($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('announcement/list-document')->with('success_msg', $msg);
        }
    }

    public function getListDocument()
    {
        $this->dataService->setSearchFormValues(Input::All());
        $q          = $this->dataService->buildDocumentListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 10;
        $details 	= $q->paginate($perPage);
        $dd_arr['status_list'] = $this->dataService->populateDocumentStatus();
        $dd_arr['category_names'] = $this->dataService->populateDocumentCategory($this->subscription_id);
        return View::make('site/announcement/listDocument', compact('details', 'dd_arr'));
    }

    public function postListDocument()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        print_r(Input::All());
        $action = Input::get('action');
        $details    = $this->dataService->updateDocumentStatus($this->subscription_id, $checked_ids, $action);
        return 'here';
        return Redirect::to('announcement/list-document')->with('success_msg', trans('general.delete_success'));
    }

    public function postDeleteDocumentAttachment()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        $document_id = Input::get('document_id', 0);
        $this->dataService->deleteDocumentAttachment($this->subscription_id, $document_id, $checked_ids);
        return Redirect::to('announcement/add-document?id='.$document_id)->with('success_msg', trans('general.delete_success'));
    }

    public function getAddDocumentAttachment()
    {
        $id = Input::get('id', 0);
        $mode = 'add';
        $details = array();
        if($id)
        {
            $details = $this->dataService->getDocumentAttachmentDetailsForEdit($this->subscription_id, $id);
            if(!$details)
            {
                return Redirect::to('announcement/add-document');
            }
            else
            {
                $mode = 'edit';
                $document_id = $details['announcement_document_id'];
            }
        }
        else
        {
            $document_id = Input::get('document_id', 0);
        }
        $dd_arr['max_file_size'] = $this->dataService->getDisplayValidationUnit('announcement_document_attachment_max_file_size');
        if($document_id)
        {
            return View::make('site/announcement/addDocumentAttachment',  compact('details', 'dd_arr', 'document_id', 'mode'));
        }
        else
            return Redirect::to('announcement/add-document');

    }
    public function postAddDocumentAttachment()
    {
        $id = Input::get('id', 0);
        $document_id = Input::get('document_id');
        $input = Input::All();
        $rules = array();
        $rules['attachment_file'] = '';
        if(!$id)
            $rules['attachment_file'] = 'Required|'.$this->dataService->getEntryValidatorRule('announcement_document', 'attachment_file', $id);
        else
            $rules['attachment_file'] .= $this->dataService->getEntryValidatorRule('announcement_document', 'attachment_file', $id);
        $input['added_by'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if(isset($input['attachment_file']) )
            {
                $file = $input['attachment_file'];
                if($file->getError())
                    return Redirect::back()->withInput()->with('error_msg', 'Sorry errors found, Invalid file size');
            }

            if($id)
            {
                $this->dataService->updateDocumentAttachment($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addDocumentAttachment($this->subscription_id, $document_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('announcement/add-document?id='.$document_id)->with('success_msg', $msg);
        }
    }
    public function getAddDocumentCategory()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getDocumentCategoryDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getDocumentCategoryListForValidate($this->subscription_id);
        return View::make('site/announcement/addDocumentCategory', compact('details', 'dd_arr'));
    }

    public function postAddDocumentCategory()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('employment_status', 'name', $id));
        $input = Input::All();
        $input['user_id'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if($id)
            {
                $this->dataService->updateDocumentCategory($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addDocumentCategory($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('announcement/list-document-category')->with('success_msg', $msg);
        }
    }

    public function getListDocumentCategory()
    {
        $details    = $this->dataService->getDocumentCategoryList($this->subscription_id, Input::All());
        return View::make('site/announcement/listDocumentCategory', compact('details'));
    }

    public function postListDocumentCategory()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteDocumentCategory($this->subscription_id, $del_ids);
        return Redirect::to('announcement/list-document-category')->with('success_msg', trans('general.delete_success'));
    }




    public function anyDownloadJobTitleSpec()
    {
        $attachment_id = Input::get('attachment_id', 0);
        $this->jobService->downloadNewsAttachment($this->subscription_id, $attachment_id);
    }

}