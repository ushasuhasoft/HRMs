<?php
class AnnouncementService
{
    public static function getFileDestination($subscription_id, $type)
    {
        //in lib add the function to get the user upload folder name
        //in site config, store the orig path i.e files/
        //get the folder name from config for the related type
        return Config::get('site.folder_path').'/'.getSubscriberFolder($subscription_id).'/'.Config::get('site.'.$type);
    }

    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
    }
    public function generateListForValidate($details, $fld_name,  $fld_key = 'id')
    {
        $return_arr = array();
        foreach($details as $rec)
        {
            if(isset($rec[$fld_key]) && isset($rec[$fld_name]))
            {
                $return_arr[] = array('id' => $rec[$fld_key],
                    'name' => $rec[$fld_name]);
            }

        }
        return $return_arr;
    }
    public static function populateNewsStatus()
    {
        return array('draft' => Lang::get('enum.news_status.draft'),
            'published' => Lang::get('enum.news_status.published'),
            'archived' => Lang::get('enum.news_status.archived'),
        );
    }
    public static function populateDocumentStatus()
    {
        return array('draft' => Lang::get('enum.news_status.draft'),
            'published' => Lang::get('enum.news_status.published'),
            'archived' => Lang::get('enum.news_status.archived'),
        );
    }

    public static function getEntryValidatorRule($type, $field, $id = 0)
    {
        $rules['announcement_news']['topic'] = 'Required';
        $rules['announcement_news']['attachment_file'] = 'Max:'.Config::get('site.announcement_news_attachment_max_file_size');

        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function getNewsDataForEdit($subscription_id, $id = 0)
    {
        return AnnouncementNewsMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addNews($subscription_id, $user_id, $data)
    {
        $obj = new AnnouncementNewsMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['topic'] = isset($data['topic']) ? $data['topic'] : '';
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        $arr['published_to_admin'] = isset($data['published_to_admin']) ? $data['published_to_admin'] : 0;
        $arr['published_to_supervisor'] = isset($data['published_to_supervisor']) ? $data['published_to_supervisor'] : 0;
        $arr['published_to_all_employees'] = isset($data['published_to_all_employees']) ? $data['published_to_all_employees'] : 0;
        $arr['date_published'] = isset($data['date_published']) ? fmtAsDbDate($data['date_published']) : new DateTime;
        $arr['status'] = isset($data['status']) ? $data['status'] :'Draft';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateNews($subscription_id, $id, $data)
    {
        $fields_arr = array("topic", "description",  "status");
        $arr = array();
        $arr['date_published'] = isset($data['date_published']) ? fmtAsDbDate($data['date_published']) : new DateTime;
        foreach($fields_arr as $fld)
        {
            if(isset($data[$fld]))
            {
                $arr[$fld] = $data[$fld];
            }
        }
        if(count($arr))
        {
            AnnouncementNewsMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }

    }
    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['topic'] = isset($input['srch_topic']) ? $input['srch_topic']: '';
        $this->srchfrm_fld_arr['status'] = isset($input['srch_status']) ? $input['srch_status']: '';
        $this->srchfrm_fld_arr['category_id'] = isset($input['srch_category_id']) ? $input['srch_category_id']: '';
    }

    public function buildNewsListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'desc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'id';
        $q = AnnouncementNewsMdl::where('subscription_id', $subscription_id);
        //handle search
        if($this->getSrchVal('topic'))
        {
            $q->WhereRaw("topic LIKE '%".addslashes($this->getSrchVal('topic'))."%'");
        }
        if($this->getSrchVal('status'))
        {
            $q->Where('status', $this->getSrchVal('status'));
        }
        $q->orderBy($order_by_field, $order_by)->get();
        return $q;
    }

    public function updateNewsStatus($subscription_id, $ids, $action)
    {
        if(is_array($ids) AND count($ids))
        {
            if($action == 'archive')
                AnnouncementNewsMdl::where('subscription_id', $subscription_id)
                    ->whereIn('id', $ids )
                    ->update(array('status' => 'archived'));
            elseif($action == 'remove')
                AnnouncementNewsMdl::where('subscription_id', $subscription_id)
                    ->whereIn('id', $ids )
                    ->delete();

        }
    }

    public function listNewsAttachment($subscription_id, $news_id)
    {
        return AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
                                    ->where('announcement_news_id', $news_id)
                                    ->get();
    }

    public function getNewsAttachmentDetailsForEdit($subscription_id, $id)
    {
        return AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
                    ->where('id', $id)
                    ->first();
    }
    public function getDisplayValidationUnit($type)
    {
        if($type == 'announcement_news_attachment_max_file_size')
        {
            return (Config::get('site.announcement_news_attachment_max_file_size') / (1024 )) . ' MB ';
        }
        if($type == 'announcement_document_attachment_max_file_size')
        {
            return (Config::get('site.announcement_document_attachment_max_file_size') / (1024 )) . ' MB ';
        }


        return '';
    }
    public function addNewsAttachment($subscription_id, $news_id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new AnnouncementNewsAttachmentMdl();
        $arr = array();
        $arr['date_added'] = new DateTime;
        $arr['added_by'] =  $data['added_by'];
        $arr['subscription_id'] =  $subscription_id;
        $arr['announcement_news_id'] =  $news_id;
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'announcement_news_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;
        }
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateNewsAttachment($subscription_id, $id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new AnnouncementNewsAttachmentMdl();
        $arr = array();
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'announcement_news_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;
            $arr['date_added'] = new DateTime;
            $arr['added_by'] =  $data['added_by'];
            $arr['subscription_id'] =  $subscription_id;
            $arr['announcement_news_id'] =  $data['announcement_news_id'];
            $this->deleteNewsAttachment($subscription_id, $data['news_id'], array($id));
            $obj->addNew($arr);
        }
        else
        {
            AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }
       // $entry_id = $obj->addNew($arr);

    }

    public function deleteNewsAttachment($subscription_id, $news_id, $checked_ids)
    {
        $this->unlinkNewsAttachment($subscription_id, $news_id, $checked_ids);
        AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('announcement_news_id', $news_id)
            ->whereIn('id', $checked_ids )
            ->delete();
    }
    public function unlinkNewsAttachment($subscription_id, $news_id, $checked_ids)
    {
        //unlink the related file
        $destinationpath = self::getFileDestination($subscription_id, 'announcement_news_attachment_folder');
        $details = AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('announcement_news_id', $news_id)
            ->whereIn('id', $checked_ids)
            ->get();
        foreach ($details as $old_rec) {
            @unlink($destinationpath . '/' . $old_rec->saved_file_name);
        }
    }
    public function getDocumentDataForEdit($subscription_id, $id = 0)
    {
        return AnnouncementDocumentMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addDocument($subscription_id, $user_id, $data)
    {
        $obj = new AnnouncementDocumentMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['topic'] = isset($data['topic']) ? $data['topic'] : '';
        $arr['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        $arr['published_to_admin'] = isset($data['published_to_admin']) ? $data['published_to_admin'] : 0;
        $arr['published_to_supervisor'] = isset($data['published_to_supervisor']) ? $data['published_to_supervisor'] : 0;
        $arr['published_to_all_employees'] = isset($data['published_to_all_employees']) ? $data['published_to_all_employees'] : 0;
        $arr['date_published'] = isset($data['date_published']) ? fmtAsDbDate($data['date_published']) : new DateTime;
        $arr['status'] = isset($data['status']) ? $data['status'] :'Draft';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateDocument($subscription_id, $id, $data)
    {
        $fields_arr = array("topic", "category_id", "description",  "status");
        $arr = array();
        $arr['date_published'] = isset($data['date_published']) ? fmtAsDbDate($data['date_published']) : new DateTime;
        foreach($fields_arr as $fld)
        {
            if(isset($data[$fld]))
            {
                $arr[$fld] = $data[$fld];
            }
        }
        if(count($arr))
        {
            AnnouncementDocumentMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }
    }

    public function buildDocumentListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'desc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'id';
        $q = AnnouncementDocumentMdl::where('subscription_id', $subscription_id);
        //handle search
        if($this->getSrchVal('topic'))
        {
            $q->WhereRaw("topic LIKE '%".addslashes($this->getSrchVal('topic'))."%'");
        }
        if($this->getSrchVal('status'))
        {
            $q->Where('status', $this->getSrchVal('status'));
        }
        if($this->getSrchVal('category_id'))
        {
            $q->Where('category_id', $this->getSrchVal('category_id'));
        }

        $q->orderBy($order_by_field, $order_by)->get();
        return $q;
    }

    public function updateDocumentStatus($subscription_id, $ids, $action)
    {
        if(is_array($ids) AND count($ids))
        {
            if($action == 'archive')
                AnnouncementDocumentMdl::where('subscription_id', $subscription_id)
                    ->whereIn('id', $ids )
                    ->update(array('status' => 'archived'));
            elseif($action == 'remove')
                AnnouncementDocumentMdl::where('subscription_id', $subscription_id)
                    ->whereIn('id', $ids )
                    ->delete();

        }
    }

    public function listDocumentAttachment($subscription_id, $document_id)
    {
        return AnnouncementDocumentAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('announcement_document_id', $document_id)
            ->get();
    }

    public function getDocumentAttachmentDetailsForEdit($subscription_id, $id)
    {
        return AnnouncementDocumentAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }

    public function addDocumentAttachment($subscription_id, $document_id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new AnnouncementDocumentAttachmentMdl();
        $arr = array();
        $arr['date_added'] = new DateTime;
        $arr['added_by'] =  $data['added_by'];
        $arr['subscription_id'] =  $subscription_id;
        $arr['announcement_document_id'] =  $document_id;
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'announcement_document_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;

        }
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateDocumentAttachment($subscription_id, $id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new AnnouncementDocumentAttachmentMdl();
        $arr = array();
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'announcement_document_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;
            $arr['date_added'] = new DateTime;
            $arr['added_by'] =  $data['added_by'];
            $arr['subscription_id'] =  $subscription_id;
            $arr['announcement_document_id'] =  $document_id;
            $this->deleteDocumentAttachment($subscription_id, $data['document_id'], array($id));
            $entry_id = $obj->addNew($arr);
        }
        else
        {
            AnnouncementDocumentAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }
        return ;
    }

    public function deleteDocumentAttachment($subscription_id, $document_id, $checked_ids)
    {

        //unlink the related file
        $this->unlinkDocumentAttachment($subscription_id, $document_id, $checked_ids);

        AnnouncementDocumentAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('announcement_document_id', $document_id)
            ->whereIn('id', $checked_ids )
            ->delete();
    }
    public function unlinkDocumentAttachment($subscription_id, $document_id, $checked_ids)
    {
        //unlink the related file
        $destinationpath = self::getFileDestination($subscription_id, 'announcement_document_attachment_folder');
        $details = AnnouncementDocumentMdl::where('subscription_id', $subscription_id)
            ->where('announcement_document_id', $document_id)
            ->whereIn('id', $checked_ids)
            ->get();
        foreach ($details as $old_rec) {
            @unlink($destinationpath . '/' . $old_rec->saved_file_name);
        }
    }

    public function getDocumentCategoryDataForEdit($subscription_id, $id = 0)
    {
        return AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addDocumentCategory($subscription_id, $user_id, $data)
    {
        $obj = new AnnouncementDocumentCategoryMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateDocumentCategory($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getDocumentCategoryList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getDocumentCategoryListForValidate($subscription_id)
    {
        $arr =  AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }
    public function populateDocumentCategory($subscription_id)
    {
        return  AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
    }

    public function deleteDocumentCategory($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            AnnouncementDocumentCategoryMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function downloadAnnouncementAttachment($subscription_id, $attachment_id, $type='news')
    {
        if($type == 'news')
        {
            $details = AnnouncementNewsAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $attachment_id)
                ->first();
        }
        else
        {
            $details = AnnouncementDocumentAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $attachment_id)
                ->first();
        }
        $file_type = ($type == 'news') ? 'announcement_news_attachment_folder' : 'announcement_document_attachment_folder';
        if($details)
        {
            $download_file_name = $details['orig_file_name'];
            $saved_file_name = $details['saved_file_name'];
            $file_path =  self::getFileDestination($subscription_id, 'announcement_news_attachment_folder');
            $download_file = $file_path.'/'.$saved_file_name;
            downloadFile($download_file_name, $download_file);
        }
        die;
    }

}
