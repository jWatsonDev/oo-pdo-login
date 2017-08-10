<?php
class User {
    private $_db, 
            $_data, 
            $_sessionName;
    
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
    }
    
    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('Problem creating account.');
        }
    }
    public function find($user = null) {
        if ($user) {
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    
    }
    public function login($username = null, $password = null) {
        $user = $this->find($username);
        if ($user) {
            if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->id);
                return true;
            }
        }
        return false;
    }
    
    private function data() {
        return $this->_data;
    }
}