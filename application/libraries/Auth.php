<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
    protected $CI;
    public $_table = null;
    public $max_login_attempts = 5;
    public $permissions_table = 'permissions';
    public $pivot_admins_permissions_table = 'admins_permissions';

    public function __construct($entity = null)
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('session');
        $this->CI->load->library('encrypt');
        $this->CI->load->helper('cookie');
        $this->_table = $entity;
    }


    /**
     * authenticate
     *
     * Authenticate(log in) user, first choose $this->_table for example 'admins' or 'users'
     *
     * @param string $identity
     * @param string $password
     * @param bool $remember
     * @param bool $check_login_attempts
     * @return bool|string
     */
    public function authenticate($identity, $password, $remember = false, $check_login_attempts = false)
    {
        $this->CI->db->select('*');
        $this->CI->db->from($this->_table);
        $this->CI->db->where('username', $identity);
        $this->CI->db->or_where('email', $identity);
        $this->CI->db->or_where('mobile', $identity);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            if ((bool)$result[0]->active != true) {
                return 'حساب کاربری شما غیرفعال می باشد';
            }
            if ($result[0]->status == 'disabled') {
                return 'حساب کاربری شما غیرفعال شده است ، به مدیر سایت اطلاع دهید';
            }
            if ((bool)$result[0]->deleted == true) {
                return 'حساب کاربری شما حذف شده است';
            }
            if ($this->verify_password($password, $result[0]->password)) {
                $this->update($result[0]->id, ['login_date' => date('Y-m-d H:i:s')]);
                $data = [
                    'id' => $result[0]->id
                ];
                $this->set_session($data, $remember);
                return true;
            }

            if ($check_login_attempts) {
                if ($this->get_login_attempts($result[0]->id)) {
                    $this->deactivate_user($result[0]->id);
                    return 'حساب کاربری شما غیرفعال می باشد';
                }
                $login_attempts = $result[0]->login_attempts;
                $login_attempts += 1;
                $this->CI->db->where('id', $result[0]->id);
                $this->CI->db->update($this->_table, ['login_attempts' => $login_attempts]);
            }

            return 'نام کاربری یا کلمه عبور شما نادرست می باشد';
        }
        return 'اطلاعات شما در سیستم موجود نیست';
    }

    /**
     * set_session
     *
     * Set session for user or set cookie for user when remember is true
     *
     * @param array $data
     * @param bool $remember
     * @return void
     */
    private function set_session($data, $remember)
    {
        $this->CI->session->set_userdata($this->_table . '_logged_in', $data);
        if ($remember) {
            $data = $this->CI->encrypt->encode(json_encode($data));
            set_cookie($this->_table . '_logged_in', $data, strtotime('+3 days'));
        }
    }

    /**
     * get_login_attempts
     *
     * GET user login attempts
     *
     * @param int $user_id
     * @return bool
     */
    public function get_login_attempts($user_id)
    {
        $this->CI->db->select('login_attempts');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("id", $user_id);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            if ($this->max_login_attempts < $result[0]->login_attempts) {
                return true;
            }
        }
        return false;
    }


    /**
     * activate user
     *
     * Activate user, active=1
     *
     * @param int $user_id
     * @return bool
     */
    public function activate_user($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $result = $this->CI->db->update($this->_table, ['active' => 1]);
        return $result;
    }

    /**
     * deactivate user
     *
     * Deactivate user, active=0
     *
     * @param int $user_id
     * @return bool
     */
    public function deactivate_user($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $result = $this->CI->db->update($this->_table, ['active' => 0]);
        return $result;
    }


    /**
     * get user_status
     *
     * Get user status, 'normal' , 'disabled'
     *
     * @param int $user_id
     * @return bool|string
     */
    public function get_status($user_id)
    {
        $this->CI->db->select('status');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("id", $user_id);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            return $result[0]->status;
        }
        return false;
    }


    /**
     * get user_type
     *
     * Get user type , 'master', 'author', 'user', 'client;
     *
     * @param int $user_id
     * @return bool|string
     */
    public function get_type($user_id)
    {
        $this->CI->db->select('type');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("id", $user_id);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            return $result[0]->type;
        }
        return false;
    }


    /**
     * get user info
     *
     * Get user info
     *
     * @param int $user_id
     * @return bool|object
     */
    public function info($user_id = null)
    {
        if (empty($user_id)) {
            //if $user_id is empty then get current use info
            $cookie = $this->decrypt_cookie($this->_table . '_logged_in');
            if (!empty($cookie)) {
                if (isset($cookie->id)) {
                    $user_id = $cookie->id;
                }
            }

            $session = $this->CI->session->userdata($this->_table . '_logged_in');

            if (!empty($session)) {
                $user_id = $session['id'];
            }

        }


        $this->CI->db->select('*');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("id", $user_id);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            return $result[0];
        }
        return false;
    }


    /**
     * is_authenticated
     *
     * Checks user is logged in or not from session or cookie
     *
     * @return bool
     */
    public function is_authenticated()
    {
        $cookie = $this->decrypt_cookie($this->_table . '_logged_in');
        if (!empty($cookie)) {
            if (isset($cookie->id)) {
                $info = $this->info();
                if (!empty($info)) {
                    if (($info->status != 'disabled') && ($info->active != 0))
                        return true;
                }
            }
        }

        $session = $this->CI->session->has_userdata($this->_table . '_logged_in');
        if ($session) {
            $info = $this->info();
            if (!empty($info)) {
                if (($info->status != 'disabled') && ($info->active != 0))
                    return true;
            }
        }

        $this->sign_out();
        return false;
    }


    /**
     * create
     *
     * Creates user , first choose $this->_table for example 'admins' or 'users'
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array $additional_data
     * @param bool $is_active
     * @param array $unique_fields
     * @return bool|string
     */
    public function create($username, $password, $email, $is_active = false, $additional_data = null, $unique_fields = null)
    {
        if (!empty($unique_fields)) {
            $is_duplicated = $this->check_unique_field($unique_fields);
            if ($is_duplicated != false) {
                return $is_duplicated;
            }
        }

        $data = array(
            'username' => $username,
            'password' => $this->hash_password($password),
            'email' => $email,
            'active' => $is_active,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($additional_data)) {
            foreach ($additional_data as $key => $value) {
                $data[$key] = $value;
            }
        }

        $this->CI->db->insert($this->_table, $data);
        return $this->CI->db->insert_id();

    }


    /**
     * check_unique_field
     *
     * Checks fields in tables['admins' or 'users'] are unique or not
     *
     * @param array $fields
     * @return bool|string
     */
    private function check_unique_field($fields)
    {
        if (!empty($fields)) {
            foreach ($fields as $key => $value) {

                $this->CI->db->select($key);
                $this->CI->db->from($this->_table);
                $this->CI->db->limit(1);
                $this->CI->db->where("{$key}", "{$value}");
                $count = $this->CI->db->count_all_results();
                if ($count > 0) {
                    switch ($key) {
                        case 'username' :
                            return 'نام کاربری تکراری می باشد';
                            break;
                        case 'email':
                            return 'ایمیل تکراری می باشد';
                            break;
                        case 'mobile' :
                            return 'شماره موبایل تکراری می باشد';
                            break;
                    }
                }
            }
        }

        return false;

    }


    /**
     * Check Username Duplication
     *
     * @param string $username
     * @return bool
     */
    public function check_username($username)
    {
        $this->CI->db->select('username');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("username", $username);
        $count = $this->CI->db->count_all_results();
        if ($count > 0) {
            return true;
        }
        return false;
    }


    /**
     * Check Email Duplication
     *
     * @param string $email
     * @return bool
     */
    public function check_email($email)
    {
        $this->CI->db->select('email');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("email", $email);
        $count = $this->CI->db->count_all_results();
        if ($count > 0) {
            return true;
        }
        return false;
    }


    /**
     * Check Mobile Duplication
     *
     * @param string $mobile
     * @return bool
     */
    public function check_mobile($mobile)
    {
        $this->CI->db->select('mobile');
        $this->CI->db->from($this->_table);
        $this->CI->db->limit(1);
        $this->CI->db->where("mobile", $mobile);
        $count = $this->CI->db->count_all_results();
        if ($count > 0) {
            return true;
        }
        return false;
    }


    /**
     * update
     *
     * Updates user , first choose $this->_table for example 'admins' or 'users'
     *
     * @param integer $id
     * @param array $data
     * @return bool|string
     */
    public function update($id, $data)
    {
        if (array_key_exists('password', $data)) {
            $data['password'] = $this->hash_password($data['password']);
        }
        $this->CI->db->where('id', $id);
        $this->CI->db->update($this->_table, $data);
        if ($this->CI->db->affected_rows() > 0) {
            return true;
        }
        return false;

    }


    /**
     * hash_password
     *
     * Hashes the password in Bluefish
     *
     * @param string $password
     * @return bool|mixed|string
     */
    public function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }


    /**
     * verify_password
     *
     * Verify the password
     *
     * @param string $password
     * @param string $hash
     * @return bool|mixed|string
     */
    public function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }


    /**
     * Sign Out
     *
     * @return void
     */
    public function sign_out()
    {
        $this->CI->session->unset_userdata($this->_table . '_logged_in');
        delete_cookie($this->_table . '_logged_in');
    }


    /**
     * Delete
     *
     * Delete user by updating deleted to 1
     *
     * @param int $user_id
     * @return bool
     */
    public function soft_delete($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update($this->_table, ['deleted' => 1]);
        if ($this->CI->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    /**
     * Delete
     *
     * Delete user
     *
     * @param int $user_id
     * @return bool
     */
    public function delete($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $result = $this->CI->db->delete($this->_table);
        return $result;
    }


    /**
     * Disable user
     *
     *
     * @param int $user_id
     * @return bool
     */
    public function disable($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update($this->_table, ['status' => 'disabled']);
        if ($this->CI->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    /**
     * Enable user
     *
     *
     * @param int $user_id
     * @return bool
     */
    public function enable($user_id)
    {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update($this->_table, ['status' => 'normal']);
        if ($this->CI->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    /**
     * Type of user
     * 'master', 'author', 'user' , 'client'
     *
     * @param int $user_id
     * @param string $type
     * @return bool
     */
    public function set_type($user_id, $type)
    {
        $this->CI->db->where('id', $user_id);
        $this->CI->db->update($this->_table, ['type' => $type]);
        if ($this->CI->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }


    /**
     * Decrypt cookie value
     *
     * cookie data are stored as JSON
     *
     * @param string $name
     * @return bool|object
     */

    public function decrypt_cookie($name)
    {
        $data = get_cookie($name);
        if (!empty($data)) {
            $data = $this->CI->encrypt->decode($data);
            return json_decode($data);
        }
        return false;

    }

    /**
     * Get All Permissions Or One
     * @param null|string $name
     * @return null|object
     */
    public function get_permissions($name = null)
    {
        $this->CI->db->select('*');
        $this->CI->db->from($this->permissions_table);
        $result = null;
        if (!empty($name)) {
            $this->CI->db->where('name', $name);
            $this->CI->db->limit(1);
            $result = $this->CI->db->get()->result();
            if (!empty($result)) {
                $result = $result[0];
            }
        } else {
            $this->CI->db->order_by("position", "asc");
            $result = $this->CI->db->get()->result();
        }
        return $result;
    }

    /**
     * Create Permission
     * @param array $data
     * @return bool|object
     */
    public function create_permission($data)
    {
        $this->CI->db->insert($this->permissions_table, $data);
        return $this->CI->db->insert_id();
    }

    /**
     * Get Admin Permissions
     * @param int $admin_id
     * @return null|object
     */
    public function get_admin_permissions($admin_id)
    {
        $this->CI->db->select('*');
        $this->CI->db->from($this->pivot_admins_permissions_table);
        $this->CI->db->join('permissions', 'admins_permissions.permission_id = permissions.id');
        $this->CI->db->where('admins_permissions.admin_id', $admin_id);
        $result = $this->CI->db->get()->result();
        return $result;
    }


    /**
     * Check Admin Has Permission
     * @param int $admin_id
     * @param string $permission
     * @return boolean
     */
    public function admin_has_permission($admin_id, $permission)
    {
        $this->CI->db->select('*');
        $this->CI->db->from($this->pivot_admins_permissions_table);
        $this->CI->db->join('permissions', 'admins_permissions.permission_id = permissions.id');
        $this->CI->db->where('permissions.name', $permission);
        $this->CI->db->where('admins_permissions.admin_id', $admin_id);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get()->result();
        if (!empty($result)) {
            return true;
        }
        return false;

    }


    /**
     * Remove Admin Permission
     * @param int $admin_id
     * @param int $permission_id
     * @return boolean
     */
    public function remove_admin_permission($admin_id, $permission_id = null)
    {
        $this->CI->db->where('admin_id', $admin_id);
        if (!empty($permission_id)) {
            $this->CI->db->where('permission_id', $permission_id);
        }
        $result = $this->CI->db->delete($this->pivot_admins_permissions_table);
        return $result;
    }

    /**
     * Set Admin Permission
     * @param array $data
     * @return boolean
     */
    public function set_admin_permission($data)
    {
        $this->CI->db->insert($this->pivot_admins_permissions_table, $data);
        return $this->CI->db->insert_id();
    }

}
