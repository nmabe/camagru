<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:52 AM
 */

class User
{
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user)
        {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user))
                {
                    $this->_isLoggedIn = true;
                }
                else{
                    $this->logOut();
                }
            }
        }else{
            $this->find($user);
        }
    }

    public function create($fields = array()){
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem registering account...');
        }
    }

    public function find($user = NULL)
    {
        if ($user)
        {
            $field = ((is_numeric($user)) ? 'id' : 'username');
            $data = $this->_db->get('users', array($field ,'=', $user));
            if ($data->count()){
                $this->_data = $data->first();
                return (true);
            }
        }
        return (false);
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if (!$username && !$password && $this->exists()){
            Session::put($this->_sessionName, $this->data()->id);
        }else {
            $user = $this->find($username);
            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    if ($remember) {
                        $hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));
                        if (!$hashCheck->count()) {
                            $hash = Hash::unique();
                            $this->_db->insert('user_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_exp'));
                    }
                    return (true);
                }
            }
        }
        return (false);
    }

    public function data()
    {
        return ($this->_data);
    }

    public function update($field = array(),$user = null)
    {
        if (!$user && $this->isLoggedIn()) {
            $user = $this->data()->id;
        }

        if (!$this->_db->update('users', $user , $field)) {
            throw new Exception('There was a problem updating user ...<br>');
        }
    }

    public function logOut()
    {
        if ($this->isLoggedIn())
        {
            DB::getInstance()->delete('user_session', array('id','=', $this->data()->id));
            Cookie::delete($this->_cookieName);
            Session::delete($this->_sessionName);
        }
    }

    public function exists()
    {
        return (!empty($this->_data) ? true : false);
    }

    public function isLoggedIn()
    {
        return ($this->_isLoggedIn);
    }

    public function hasPermissions($key)
    {
        $group = $this->_db->get('`group`', array('id','=', $this->data()->group));
        if ($group->count())
        {
            $permissions = json_decode($group->first()->permission, true);
            if ($permissions[$key] == true)
            {
                return (true);
            }
        }
        return (false);
    }
}