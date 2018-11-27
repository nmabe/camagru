<?php

class Save{
    private     $_db = null,
                $_user = null,
                $_error = null,
                $_passed = false;

    const       WOI = 100;

    public function __construct($username)
    {
        $this->_db = DB::getInstance();
        $this->_user = new User();
    }

    private function exists(){
        if ($this->_user->data()){
            $exists =  DB::getInstance()->get('images', array('user_id', '=', $this->_user->data()->id));
            return (($exists) ? true : false);
        }
        return (false);
    }

    public function upload()
    {
        $fileName = escape($_FILES['isthombe']['name']);
        if (!file_exists("img/".$this->_user->data()->username.'/uploads'))
            mkdir("img/".$this->_user->data()->username."/uploads", 0777, true);
        $fileNameTmp = $_FILES['isthombe']['tmp_name'];
        $filePath = "img/".$this->_user->data()->username ."/uploads".'/'.$fileName;
        $image =  $this->_db->get('images', array('user_id', '=', $this->_user->data()->id));
        if($this->valid($filePath, $fileNameTmp))
        {
                if(!$image->count())
                {
                    $newbin_data = array(
                        array(
                            'name' => $fileName,
                            'uploaded' => date('Y-m-i H:m:s'),
                            'likes' => 0,
                            'path' => $filePath,
                            'comments' => array()
                        ));
                        $bin_data = serialize($newbin_data);
                        try{
                            $this->_db->insert('images', array(
                                'img' => $bin_data,
                                'user_id' => $this->_user->data()->id
                            ));
                        } catch (PDOException $e){
                            throw new pdoException($e->getMessage(), (int)$e->getCode());
                        }
                }
                else
                {
                    $bin_data = unserialize($image->first()->img);
                    $bin_data[] = array(
                            'name' => $fileName,
                            'uploaded' => date('Y-m-i H:m:s'),
                            'likes' => 0,
                            'path' => $filePath,
                            'comments' => array()
                    );
                    $bin_data = serialize($bin_data);
                    try{
                        $this->_db->update('images', $image->first()->id , array(
                            'img' => $bin_data
                        ));
                    } catch (PDOException $e)
                    {
                        throw new pdoException($e->getMessage(), (int)$e->getCode());
                    }
                }
                if (move_uploaded_file($fileNameTmp, $filePath))
                {
                    return (true);
                }
        }
        return (false);
    }

    public function snapLoad($filename, $data)
    {
        if (!file_exists("img/".$this->_user->data()->username.'/snaps'))
            mkdir("img/".$this->_user->data()->username."/snaps", 0777, true);
        $filePath = "img/".$this->_user->data()->username ."/snaps".'/'.$filename;
        $image =  $this->_db->get('images', array('user_id', '=', $this->_user->data()->id));
        $filePath = "img/".$this->_user->data()->username.'/snaps/'.$filename;
        $filename = $filename;
        if(!$image->count())
        {
            $newbin_data = array(
            array(
                    'name' => $filename,
                    'uploaded' => date('Y-m-i H:m:s'),
                    'likes' => 0,
                    'path' => $filePath,
                    'comments' => array()
                ));
            $bin_data = serialize($newbin_data);
            try{
                $this->_db->insert('images', array(
                'img' => $bin_data,
                'user_id' => $this->_user->data()->id
                ));
            } catch (PDOException $e){
                throw new pdoException($e->getMessage(), (int)$e->getCode());
            }
        }
        else
        {
            $bin_data = unserialize($image->first()->img);
            $bin_data[] = array(
                'name' => $filename,
                'uploaded' => date('Y-m-i H:m:s'),
                'likes' => 0,
                'path' => $filePath,
                'comments' => array()
            );
            $bin_data = serialize($bin_data);
            try{
                $this->_db->update('images', $image->first()->id , array(
                    'img' => $bin_data
                ));
            } catch (PDOException $e)
            {
                throw new pdoException($e->getMessage(), (int)$e->getCode());
            }
        }
        if (file_put_contents($filePath, $data))
        {   
            return(true);
        }
        return (false);
    }

    public function passed()
    {
        return ($this->_passed);
    }

    private function addError($error)
    {
        $this->_error[] = $error;
    }

    public function error()
    {
        return ($this->_error);
    }

    
    private function valid($targetFile, $targetFileTmp)
    {
        $fileName = $_FILES['isthombe']['name'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (file_exists($targetFile))
        {
            $this->addError("{$fileName} Already exists");
        }
        if ($_FILES['isthombe']['size'] > 5000000 || $_FILES['isthombe']['size'] == 0)
        {
            if ($_FILES['isthombe']['size'] > 5000000)
                $this->addError("{$fileName} File of {$_FILES['isthombe']['size']} bytes is Too Large for Upload");    
            else
                $this->addError("{$fileName} Cannot push empty files or {$_FILES['isthombe']['size']} byte files");
        }
        if ($fileType !== 'jpg' && $fileType !== 'jpeg' && $fileType !== 'gif' && $fileType !== 'png')
        {
            $this->addError("Unknown file Type {$fileName}");
        }
        if (empty($this->_error)){
            return (true);
        }
        else
            return (false);
    }
}

?>