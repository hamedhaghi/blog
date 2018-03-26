<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploader extends CI_Controller
{
    //for ckeditor drag and drop image upload

    public function upload()
    {
        $data['uploaded'] = 0;
        $data['fileName'] = '';
        $data['url'] = '';


        if (isset($_FILES['upload'])) {
            $path = './uploads/products/';
            $config = Functions::file_upload_config($path);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('upload')) {
                $errors = array('errors' => $this->upload->display_errors());
                $message = null;
                foreach ($errors as $er) {
                    $message .= "<li>" . $er . "</li>";
                }
                $data['error'] = ['message' => $message];

            } else {
                $file_data = $this->upload->data();
                $data['uploaded'] = 1;
                $data['fileName'] = $file_data['file_name'];
                $data['url'] = base_url('uploads/products/' . $file_data['file_name']);
            }
        }

       echo json_encode($data);
    }

}
