<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function admin()
    {

        if ($this->input->post()) {
            // Form input validation
            $this->load->library('form_validation');
            $this->form_validation->set_rules('identity', 'Username', 'required', array(
                'required' => 'نام کاربری را وارد نمایید'
            ));
            $this->form_validation->set_rules('password', 'Password', 'required', array(
                'required' => 'رمزعبور را وارد نمایید'
            ));
            if ($this->form_validation->run() == false) {
                Functions::old('error', 'yes');
                Functions::old('text', $this->form_validation->error_string());
                redirect('login/admin');
            }
            // ---- Form input validation
            $identity = $this->input->post('identity', true);
            $password = $this->input->post('password', true);
            $auth = new Auth('admins');
            $result = $auth->authenticate($identity, $password);
            if ($result === true) {
                redirect('dashboard');
            } else {
                Functions::old('error', 'yes');
                Functions::old('text', $result);
                redirect('login/admin');
            }
        }
        $this->load->view('dashboard/login');
    }


}
