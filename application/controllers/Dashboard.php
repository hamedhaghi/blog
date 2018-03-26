<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Illuminate\Database\Query\Expression as DB;

class Dashboard extends AdminController
{
    private $per_page = 10;


    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {


        $data['title'] = 'داشبورد';

        $this->load->model('Post_model');
        $data['posts'] = Post_model::all()->count();

        $this->load->model('User_model');
        $data['users'] = User_model::all()->count();
        $data['latest_users'] = User_model::limit(5)->latest()->get();


        $this->load->model('Message_model');
        $data['messages'] = Message_model::limit(5)->latest()->get();

        $this->load->view('dashboard/index', $data);

    }

    public function profile()
    {

        //----- POST
        if ($this->input->post()) {
            //update
            $admin_id = $this->input->post('admin_id', true);
            if (empty($admin_id)) {
                redirect('dashboard/profile');
            }
            // Form input validation
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]', array(
                'required' => 'نام کاربری را وارد نمایید',
                'min_length' => 'نام کاربری حداقل 5 کاراکتر می باشد'

            ));
            $this->form_validation->set_rules('current_password', 'Current Password', 'required', array(
                'required' => 'کلمه عبور فعلی را وارد نمایید'
            ));
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array(
                'required' => 'ایمیل را وارد نمایید',
                'valid_email' => 'ایمیل نامعتبر می باشد'
            ));
            if ($this->form_validation->run() == false) {
                Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
                redirect('dashboard/profile');
            }
            // ---- Form input validation

            $data = null;

            $username = $this->input->post('username', true);
            if (preg_match('/[^A-Za-z0-9]+/', $username)) {
                Functions::alert("<li>نام کاربری میتواند ترکیبی از اعداد و حروف انگلیسی باشد</li>", 'warning');
                redirect('dashboard/profile');
            }
            $current_password = $this->input->post('current_password', true);
            $password = $this->input->post('password', true);
            $email = $this->input->post('email', true);
            $name = $this->input->post('name', true);
            $family = $this->input->post('family', true);

            $auth = new Auth('admins');
            $admin = $auth->info($admin_id);
            $is_verified = $auth->verify_password($current_password, $admin->password);
            if (!$is_verified) {
                Functions::alert("<li>کلمه عبور فعلی اشتباه می باشد</li>", 'warning');
                redirect('dashboard/profile');
            }

            if (!empty(trim($password))) {
                if (strlen($password) < 5) {
                    Functions::alert("<li>کلمه عبور حداقل 5 کاراکتر می باشد</li>", 'warning');
                    redirect('dashboard/profile');
                }
            }

            if ($username != $admin->username) {
                if ($auth->check_username($username)) {
                    Functions::alert("<li>نام کاربری تکراری می باشد</li>", 'warning');
                    redirect('dashboard/profile');
                }
            }

            if ($email != $admin->email) {
                if ($auth->check_email($email)) {
                    Functions::alert("<li>ایمیل تکراری می باشد</li>", 'warning');
                    redirect('dashboard/profile');
                }
            }


            $data = [
                'username' => $username,
                'email' => $email,
                'name' => $name,
                'family' => $family
            ];

            if (!empty($password)) {
                $data['password'] = $password;
            }

            if (!empty(trim($_FILES['file']['size']))) { // if any file has been uploaded
                $path = FCPATH . 'uploads/profiles';
                Functions::make_directory($path);
                $config = Functions::file_upload_config($path);
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    $errors = array('errors' => $this->upload->display_errors());
                    $message = null;
                    foreach ($errors as $er) {
                        $message .= "<li>" . $er . "</li>";
                    }
                    Functions::alert($message, 'danger');
                    redirect('dashboard/profile');
                } else {
                    $file_data = $this->upload->data();
                    $data['picture'] = $file_data['file_name'];
                }
            }


            $result = $auth->update($admin_id, $data);
            if ($result) {
                Functions::alert("<li>اطلاعات ویرایش شد</li>", 'success');
            } else {
                Functions::alert("<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>", 'danger');
            }
            redirect('dashboard/profile');

        }
        //----- END OF POST


        //----- GET
        $auth = new Auth('admins');
        $data['admin'] = $auth->info();
        $data['title'] = 'پروفایل من';
        $this->load->view('dashboard/profile/index', $data);

    }

    public function logout()
    {
        $auth = new Auth('admins');
        $auth->sign_out();
        redirect('login/admin');
    }

    public function posts()
    {


        $posts = null;

        if ($this->input->post()) {
            //search
            $keyword = $this->input->post('keyword', true);
            if (empty($keyword)) {
                redirect('dashboard/posts');
            }
            redirect("dashboard/posts/?keyword={$keyword}");

        } else {
            //index

            $page_num = $this->input->get('per_page', true);
            $keyword = $this->input->get('keyword', true);

            $this->load->library('pagination');
            $this->load->model('Comment_model');
            $this->load->model('Post_model');
            $post_model = new Post_model();

            if (!empty($keyword)) {
                //so load search results
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/posts/?keyword={$keyword}"),
                    "rows" => $post_model::where('name', 'LIKE', "%{$keyword}%")->orWhere('description', 'LIKE', "%{$keyword}%")->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE
                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];
                $posts = $post_model::where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->offset($offset)
                    ->limit($config['per_page'])
                    ->latest()
                    ->get();

            } else {
                //all
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/posts/"),
                    "rows" => $post_model::all()->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE


                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];

                $posts = $post_model->offset($offset)->limit($config['per_page'])->latest()->get();

            }
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["posts"] = $posts;
        $data['title'] = 'پست ها';
        $this->load->view('dashboard/post/index', $data);

    }

    public function post_create()
    {
        $style = ['bootstrap-toggle.css', 'bootstrap_file_field.css', 'select2.min.css', 'select2-bootstrap.min.css', 'kamadatepicker.min.css', 'jquery-confirm.min.css'];
        $script = ['ckeditor/ckeditor.js', 'ckeditor-config.js', 'bootstrap-toggle.min.js', 'bootstrap_file_field.js', 'fileupload-config.js', 'persianslug.js', 'slug.js', 'select2.min.js', 'tags.js', 'kamadatepicker.min.js', 'persian-datepicker-config.js', 'jquery-confirm.min.js', 'jquery-ui.js'];



        $data['style'] = $style;
        $data['script'] = $script;
        $data['title'] = 'افزودن پست';

        $this->load->model('Tag_model');
        $data['tags'] = Tag_model::all();
        $this->load->model('Category_model');
        $categories = Category_model::where('parent_id', 0)->latest()->get();
        $data['categories'] = $categories;

        $path = FCPATH . 'uploads';
        $data['path'] = $path;
        $data['files'] = null;
        if (file_exists($path)) {
            $data['files'] = $this->fetch_files($path);
        }

        $this->load->view('dashboard/post/create', $data);
    }


    public function post_store()
    {


        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));
        $this->form_validation->set_rules('slug', 'Slug', 'required', array(
            'required' => 'نشانی اینترنتی را وارد نمایید'
        ));
        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            $this->post_keep_old();
            redirect('dashboard/post_create');
        }
        // ---- Form input validation


        $this->load->model('Post_model');
        $auth = new Auth('admins');
        $post_model = new Post_model();
        $data = null;
        $picture = null;

        if (!empty(trim($_FILES['file']['size']))) { // if any file has been uploaded
            $path = FCPATH . 'uploads/posts';
            Functions::make_directory($path);
            $config = Functions::file_upload_config($path);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $errors = array('errors' => $this->upload->display_errors());
                $message = null;
                foreach ($errors as $er) {
                    $message .= "<li>" . $er . "</li>";
                }

                Functions::alert($message, 'danger');
                $this->post_keep_old();
                redirect('dashboard/post_create');
            } else {
                $file_data = $this->upload->data();
                $picture = $file_data['file_name'];

            }
        }


        $tags = $this->input->post('tags', true);
        $slug = url_title($this->input->post('slug', true));
        if (empty($slug)) {
            $slug = url_title($this->input->post('name', true));
        }
        //check slug duplication
        while (Post_model::where('slug', '=', $slug)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }
        $post_model->name = $this->input->post('name', true);
        $post_model->slug = $slug;
        $post_model->description = $this->input->post('description');
        $post_model->visible = (bool)$this->input->post('visible', true);
        $post_model->admin_id = $auth->info()->id;
        $post_model->picture = $picture;
        $post_model->published_at = Functions::jalali_to_gregorian($this->input->post('published_at', true)) . ' ' . date('H:i:s', time());
        $post_model->seo_params = json_encode(['keyword' => $this->input->post('meta_keyword', true), 'description' => strip_tags($this->input->post('meta_description', true))]);
        $post_model->category_id = empty($this->input->post('category_id', true)) ? null : $this->input->post('category_id', true);
        $result = $post_model->save();
        if ($result) {
            if (!empty($tags)) {
                $this->load->model('Tag_model');
                $tag_model = new Tag_model();
                $tag_ids = array();
                foreach ($tags as $tag) {
                    $db_tag = Tag_model::where('slug', url_title($tag))->first();
                    if (empty($db_tag)) {
                        $tag_model = new Tag_model();
                        $tag_model->name = $tag;
                        $tag_model->slug = url_title($tag);
                        $tag_model->save();
                        $tag_ids[] = $tag_model->id;

                    } else {
                        $tag_ids[] = $db_tag->id;
                    }
                }

                $post_model->tags()->attach($tag_ids);
            }


            Functions::alert('<li>اطلاعات ذخیره شد</li>', 'success');
        } else {
            $this->post_keep_old();
            Functions::alert('<li>عدم ذخیره سازی اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect('dashboard/post_create');
    }

    public function post_edit($post_id = null)
    {
        if (empty($post_id)) {
            redirect('dashboard/posts');
        }

        $style = ['bootstrap-toggle.css', 'bootstrap_file_field.css', 'select2.min.css', 'select2-bootstrap.min.css', 'kamadatepicker.min.css', 'jquery-confirm.min.css'];
        $script = ['ckeditor/ckeditor.js', 'ckeditor-config.js', 'bootstrap-toggle.min.js', 'bootstrap_file_field.js', 'fileupload-config.js', 'persianslug.js', 'slug.js', 'select2.min.js', 'tags.js', 'kamadatepicker.min.js', 'persian-datepicker-config.js', 'jquery-confirm.min.js', 'jquery-ui.js'];
        $data['style'] = $style;
        $data['script'] = $script;
        $data['title'] = 'ویرایش پست';
        $this->load->model('Tag_model');
        $this->load->model('Post_model');
        $post = Post_model::find($post_id);
        $data['post'] = $post;
        $data['tags'] = Tag_model::all();

        $old_tags = null;
        if (count($post->tags) > 0) {
            foreach ($post->tags as $tag) {
                $old_tags[] = $tag->id;
            }
        }

        $data['old_tags'] = $old_tags;
        $this->load->model('Category_model');
        $categories = Category_model::where('parent_id', 0)->latest()->get();
        $data['categories'] = $categories;

        $path = FCPATH . 'uploads';
        $data['path'] = $path;
        $data['files'] = null;
        if (file_exists($path)) {
            $data['files'] = $this->fetch_files($path);
        }
        $this->load->view('dashboard/post/edit', $data);

    }

    public function post_update()
    {

        $post_id = $this->input->post('post_id', true);
        if (empty($post_id)) {
            redirect('dashboard/posts');
        }


        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));
        $this->form_validation->set_rules('slug', 'Slug', 'required', array(
            'required' => 'نشانی اینترنتی را وارد نمایید'
        ));
        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect("dashboard/post_edit/{$post_id}");
        }
        // ---- Form input validation


        $this->load->model('Post_model');
        $auth = new Auth('admins');
        $post_model = Post_model::find($post_id);

        $data = null;
        $picture = $this->input->post('old_picture', true);

        if (!empty(trim($_FILES['file']['size']))) { // if any file has been uploaded
            $path = FCPATH . 'uploads/posts';
            Functions::make_directory($path);
            $config = Functions::file_upload_config($path);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $errors = array('errors' => $this->upload->display_errors());
                $message = null;
                foreach ($errors as $er) {
                    $message .= "<li>" . $er . "</li>";
                }

                Functions::alert($message, 'danger');
                redirect("dashboard/post_edit/{$post_id}");
            } else {
                //delete old
                if (file_exists($path . "/{$picture}")) {
                    unlink($path . "/{$picture}");
                }
                $file_data = $this->upload->data();
                $picture = $file_data['file_name'];
            }
        }


        $tags = $this->input->post('tags', true);
        $slug = $this->input->post('slug', true);
        $slug = url_title($this->input->post('slug', true));
        if (empty($slug)) {
            $slug = url_title($this->input->post('name', true));
        }
        //check slug duplication
        while (Post_model::where('slug', '=', $slug)->where('id', '!=', $post_id)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }

        $post_model->name = $this->input->post('name', true);
        $post_model->slug = $slug;
        $post_model->description = $this->input->post('description');
        $post_model->visible = (bool)$this->input->post('visible', true);
        $post_model->admin_id = $auth->info()->id;
        $post_model->picture = $picture;
        $post_model->published_at = Functions::jalali_to_gregorian($this->input->post('published_at', true)) . ' ' . date('H:i:s', time());
        $post_model->seo_params = json_encode(['keyword' => $this->input->post('meta_keyword', true), 'description' => strip_tags($this->input->post('meta_description', true))]);
        $post_model->category_id = empty($this->input->post('category_id', true)) ? null : $this->input->post('category_id', true);
        $result = $post_model->save();
        if ($result) {
            $this->load->model('Tag_model');
            $tag_model = new Tag_model();
            $tag_ids = array();
            if (!empty($tags)) {

                foreach ($tags as $tag) {
                    $db_tag = Tag_model::where('slug', url_title($tag))->first();
                    if (empty($db_tag)) {
                        $tag_model = new Tag_model();
                        $tag_model->name = $tag;
                        $tag_model->slug = url_title($tag);
                        $tag_model->save();
                        $tag_ids[] = $tag_model->id;
                    } else {
                        $tag_ids[] = $db_tag->id;
                    }
                }
            }
            $post_model->tags()->sync($tag_ids);
            Functions::alert('<li>اطلاعات ویرایش شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect("dashboard/post_edit/{$post_id}");
    }

    public function post_delete($post_id = null)
    {
        if (empty($post_id)) {
            redirect('dashboard/posts');
        }

        $this->load->model('Post_model');
        $post_model = Post_model::find($post_id);
        if (empty($post_model)) {
            redirect('dashboard/posts');
        }


        if ($post_model->delete($post_id)) {
            //-- delete picture
            if (!empty($post_model->picture) && file_exists(FCPATH . "uploads/posts/{$post_model->picture}")) {
                unlink(FCPATH . "uploads/posts/{$post_model->picture}");
            }
            Functions::alert('<li>اطلاعات حذف شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }

        redirect('dashboard/posts');
    }

    public function post_visible($post_id = null)
    {
        if (empty($post_id)) {
            redirect('dashboard/posts');
        }

        $this->load->model('Post_model');
        $post_model = Post_model::find($post_id);
        if (!empty($post_model)) {
            switch ($post_model->visible) {
                case 1 :
                    $post_model->visible = 0;
                    break;
                case 0 :
                    $post_model->visible = 1;
                    break;
            }
            if ($post_model->save()) {
                Functions::alert('<li>اطلاعات ویرایش شد</li>', 'success');
            } else {
                Functions::alert('<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>', 'danger');
            }
        }

        redirect('dashboard/posts');
    }

    public function post_keep_old()
    {
        Functions::old('name', $this->input->post('name', true));
        Functions::old('slug', $this->input->post('slug', true));
        Functions::old('description', $this->input->post('description'));
        Functions::old('visible', $this->input->post('visible', true));
        Functions::old('meta_keyword', $this->input->post('meta_keyword', true));
        Functions::old('meta_description', $this->input->post('meta_description', true));

    }


    public function tags()
    {
        $tags = null;

        if ($this->input->post()) {
            //search
            $keyword = $this->input->post('keyword', true);
            if (empty($keyword)) {
                redirect('dashboard/tags');
            }
            redirect("dashboard/tags/?keyword={$keyword}");

        } else {
            //index

            $page_num = $this->input->get('per_page', true);
            $keyword = $this->input->get('keyword', true);

            $this->load->library('pagination');
            $this->load->model('Tag_model');
            $tag_model = new Tag_model();

            if (!empty($keyword)) {
                //so load search results
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/tags/?keyword={$keyword}"),
                    "rows" => $tag_model::where('name', 'LIKE', "%{$keyword}%")->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE
                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];
                $tags = $tag_model::where('name', 'LIKE', "%{$keyword}%")
                    ->offset($offset)
                    ->limit($config['per_page'])
                    ->latest()
                    ->get();

            } else {
                //all
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/tags/"),
                    "rows" => $tag_model::all()->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE

                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];

                $tags = $tag_model->offset($offset)->limit($config['per_page'])->latest()->get();

            }
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["tags"] = $tags;
        $data['title'] = 'تگ ها';
        $this->load->view('dashboard/tag/index', $data);

    }


    public function tag_create()
    {

        $data['title'] = 'افزودن تگ';
        $this->load->view('dashboard/tag/create', $data);
    }


    public function tag_store()
    {
        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect('dashboard/tag_create');
        }
        // ---- Form input validation

        $this->load->model('Tag_model');
        $tag_model = new Tag_model();


        $slug = url_title($this->input->post('name', true));

        //check slug duplication
        while (Tag_model::where('slug', '=', $slug)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }

        $tag_model->name = $this->input->post('name', true);
        $tag_model->slug = $slug;


        if ($tag_model->save()) {
            Functions::alert('<li>اطلاعات ذخیره شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ذخیره سازی اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect('dashboard/tag_create');

    }


    public function tag_edit($tag_id = null)
    {
        if (empty($tag_id)) {
            redirect('dashboard/tags');
        }
        $this->load->model('Tag_model');
        $data['tag'] = Tag_model::find($tag_id);
        $data['title'] = 'ویرایش تگ';
        $this->load->view('dashboard/tag/edit', $data);
    }

    public function tag_update()
    {

        $tag_id = $this->input->post('tag_id', true);
        if (empty($tag_id)) {
            redirect('dashboard/tags');
        }

        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect("dashboard/tag_edit/{$tag_id}");
        }
        // ---- Form input validation

        $this->load->model('Tag_model');
        $tag_model = Tag_model::find($tag_id);

        $slug = url_title($this->input->post('name', true));

        //check slug duplication
        while (Tag_model::where('slug', '=', $slug)->where('id', '!=', $tag_id)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }


        $tag_model->name = $this->input->post('name', true);
        $tag_model->slug = $slug;


        if ($tag_model->save()) {
            Functions::alert('<li>اطلاعات ویرایش شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect("dashboard/tag_edit/{$tag_id}");
    }

    public function tag_delete($tag_id = null)
    {
        if (empty($tag_id)) {
            redirect("dashboard/tags");
        }
        $this->load->model('Tag_model');
        if (Tag_model::destroy($tag_id)) {
            Functions::alert('<li>اطلاعات حذف شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect("dashboard/tags");
    }


    public function users()
    {
        $users = null;

        if ($this->input->post()) {
            //search
            $keyword = $this->input->post('keyword', true);
            if (empty($keyword)) {
                redirect('dashboard/users');
            }
            redirect("dashboard/users/?keyword={$keyword}");

        } else {
            //index

            $page_num = $this->input->get('per_page', true);
            $keyword = $this->input->get('keyword', true);

            $this->load->library('pagination');
            $this->load->model('User_model');
            $user_model = new User_model();

            if (!empty($keyword)) {
                //so load search results
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/users/?keyword={$keyword}"),
                    "rows" => $user_model::where('username', 'LIKE', "%{$keyword}%")
                        ->orWhere('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('family', 'LIKE', "%{$keyword}%")
                        ->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE

                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];
                $users = $user_model::where('username', 'LIKE', "%{$keyword}%")
                    ->orWhere('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('family', 'LIKE', "%{$keyword}%")
                    ->offset($offset)
                    ->limit($config['per_page'])
                    ->latest()
                    ->get();

            } else {
                //all
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/users/"),
                    "rows" => $user_model::all()->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE
                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];

                $users = $user_model->offset($offset)->limit($config['per_page'])->latest()->get();

            }
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["users"] = $users;
        $data['title'] = 'کاربران';
        $this->load->view('dashboard/user/index', $data);
    }

    public function user_create()
    {
        $data['title'] = 'افزودن کاربر';
        $this->load->view('dashboard/user/create', $data);
    }

    public function user_store()
    {
        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|is_unique[users.username]', array(
            'required' => 'نام کاربری را وارد نمایید',
            'min_length' => 'نام کاربری حداقل 5 کاراکتر می باشد',
            'is_unique' => 'نام کاربری تکراری می باشد'
        ));
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]', array(
            'required' => 'کلمه عبور را وارد نمایید',
            'min_length' => 'کلمه عبور حداقل 5 کاراکتر می باشد'
        ));
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]', array(
            'required' => 'ایمیل را وارد نمایید',
            'valid_email' => 'ایمیل نامعتبر می باشد',
            'is_unique' => 'ایمیل تکراری می باشد'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            $this->user_keep_old();
            redirect("dashboard/user_create");
        }
        // ---- Form input validation

        $username = $this->input->post('username', true);
        if (preg_match('/[^A-Za-z0-9]+/', $username)) {
            Functions::alert("<li>نام کاربری میتواند ترکیبی از اعداد و حروف انگلیسی باشد</li>", 'warning');
            $this->user_keep_old();
            redirect("dashboard/user_create");
        }

        $picture = null;
        if (!empty(trim($_FILES['file']['size']))) { // if any file has been uploaded
            $path = FCPATH . 'uploads/profiles';
            Functions::make_directory($path);
            $config = Functions::file_upload_config($path);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $errors = array('errors' => $this->upload->display_errors());
                $message = null;
                foreach ($errors as $er) {
                    $message .= "<li>" . $er . "</li>";
                }
                Functions::alert($message, 'danger');
                $this->user_keep_old();
                redirect('dashboard/user_create');
            } else {
                $file_data = $this->upload->data();
                $picture = $file_data['file_name'];
            }
        }

        $password = $this->input->post('password', true);
        $email = $this->input->post('email', true);
        $active = $this->input->post('active', true);
        $status = $this->input->post('status', true) == 1 ? 'disabled' : 'normal';
        $name = $this->input->post('name', true);
        $family = $this->input->post('family', true);
        $mobile = $this->input->post('mobile', true);
        $address = $this->input->post('address', true);

        $auth = new Auth('users');
        $result = $auth->create($username, $password, $email, $active, [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'status' => $status,
            'address' => $address,
            'picture' => $picture
        ]);

        if ($result) {
            Functions::alert("<li>اطلاعات ذخیره شد</li>", 'success');
        } else {
            $this->user_keep_old();
            Functions::alert("<li>عدم ذخیره سازی اطلاعات ، دوباره تلاش کنید</li>", 'danger');
        }

        redirect('dashboard/user_create');

    }

    public function user_edit($user_id = null)
    {
        if (empty($user_id) && !is_numeric($user_id)) {
            redirect('dashboard/users');
        }

        $this->load->model('User_model');
        $user = User_model::find($user_id);
        if (empty($user)) {
            redirect('dashboard/users');
        }

        $data['user'] = $user;
        $data['title'] = 'ویرایش کاربر';
        $this->load->view('dashboard/user/edit', $data);
    }


    public function user_update()
    {
        $user_id = $this->input->post('user_id', true);
        if (empty($user_id)) {
            redirect('dashboard/users');
        }

        $auth = new Auth('users');
        $user = $auth->info($user_id);

        if (empty($user)) {
            redirect('dashboard/users');
        }

        $is_unique_username = null;
        $is_unique_email = null;

        if ($user->username != $this->input->post('username', true)) {
            $is_unique_username = '|is_unique[users.username]';
        }

        if ($user->email != $this->input->post('email', true)) {
            $is_unique_email = '|is_unique[users.email]';
        }

        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[5]' . $is_unique_username, array(
            'required' => 'نام کاربری را وارد نمایید',
            'min_length' => 'نام کاربری حداقل 5 کاراکتر می باشد',
            'is_unique' => 'نام کاربری تکراری می باشد'
        ));
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email' . $is_unique_email, array(
            'required' => 'ایمیل را وارد نمایید',
            'valid_email' => 'ایمیل نامعتبر می باشد',
            'is_unique' => 'ایمیل تکراری می باشد'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect("dashboard/user_edit/{$user_id}");
        }
        // ---- Form input validation


        if (preg_match('/[^A-Za-z0-9]+/', $this->input->post('username', true))) {
            Functions::alert("<li>نام کاربری میتواند ترکیبی از اعداد و حروف انگلیسی باشد</li>", 'warning');
            redirect("dashboard/user_edit/{$user_id}");
        }

        $picture = $this->input->post('old_picture', true);
        $data = [
            'username' => $this->input->post('username', true),
            'email' => $this->input->post('email', true),
            'active' => $this->input->post('active', true),
            'name' => $this->input->post('name', true),
            'family' => $this->input->post('family', true),
            'mobile' => $this->input->post('mobile', true),
            'status' => $this->input->post('status', true) == 1 ? 'disabled' : 'normal',
            'address' => $this->input->post('address', true)

        ];

        if (!empty(trim($this->input->post('password', true))) && strlen($this->input->post('password', true)) >= 5) {
            $data['password'] = $this->input->post('password', true);
        } elseif (!empty(trim($this->input->post('password', true))) && strlen($this->input->post('password', true)) < 5) {
            Functions::alert("<li>کلمه عبور حداقل 5 کاراکتر می باشد</li>", 'warning');
            redirect("dashboard/user_edit/{$user_id}");
        }

        if (!empty(trim($_FILES['file']['size']))) { // if any file has been uploaded
            $path = FCPATH . 'uploads/profiles';
            Functions::make_directory($path);
            $config = Functions::file_upload_config($path);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $errors = array('errors' => $this->upload->display_errors());
                $message = null;
                foreach ($errors as $er) {
                    $message .= "<li>" . $er . "</li>";
                }
                Functions::alert($message, 'danger');
                redirect("dashboard/user_edit/{$user_id}");
            } else {
                if (!empty($picture) && file_exists($path . "/{$picture}")) {
                    unlink($path . "/{$picture}");
                }
                $file_data = $this->upload->data();
                $picture = $file_data['file_name'];
            }
        }

        $data['picture'] = $picture;

        $result = $auth->update($user_id, $data);

        if ($result) {
            Functions::alert("<li>اطلاعات ویرایش شد</li>", 'success');
        } else {

            Functions::alert("<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>", 'danger');
        }
        redirect("dashboard/user_edit/{$user_id}");

    }

    public function user_delete($user_id = null)
    {
        if (empty($user_id) && !is_numeric($user_id)) {
            redirect("dashboard/users");
        }

        $auth = new Auth('users');
        $user = $auth->info($user_id);
        if (empty($user)) {
            redirect("dashboard/users");
        }

        if (!empty(trim($user->picture)) && file_exists(FCPATH . "uploads/profiles/{$user->picture}")) {
            unlink(FCPATH . "uploads/profiles/{$user->picture}");
        }

        if ($auth->delete($user_id)) {
            Functions::alert("<li>اطلاعات حذف شد</li>", 'success');
        } else {
            Functions::alert("<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>", 'danger');
        }

        redirect("dashboard/users");
    }

    public function user_active($user_id = null)
    {
        if (empty($user_id) && !is_numeric($user_id)) {
            redirect('dashboard/users');
        }

        $auth = new Auth('users');
        $user = $auth->info($user_id);
        if (empty($user)) {
            redirect('dashboard/users');
        }
        $result = false;
        if ($user->active == 1) {
            $result = $auth->update($user_id, ['active' => 0]);
        } elseif ($user->active == 0) {
            $result = $auth->update($user_id, ['active' => 1]);
        }

        if ($result) {
            Functions::alert("<li>اطلاعات ویرایش شد</li>", 'success');
        } else {

            Functions::alert("<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>", 'danger');
        }
        redirect("dashboard/users");

    }

    public function user_keep_old()
    {
        Functions::old('username', $this->input->post('username', true));
        Functions::old('email', $this->input->post('email', true));
        Functions::old('name', $this->input->post('name', true));
        Functions::old('family', $this->input->post('family', true));
        Functions::old('mobile', $this->input->post('mobile', true));
        Functions::old('address', $this->input->post('address', true));
    }


    public function pages($type = null)
    {
        $this->load->model('Page_model');
        if ($this->input->post()) {
            //post
            $page_model = Page_model::where('slug', '=', $this->input->post('type', true))->first();
            if (empty($page_model)) {
                //create
                $page_model = new Page_model();
            }

            $page_model->name = $this->input->post('name', true);
            $page_model->description = $this->input->post('description', true);
            $page_model->slug = $this->input->post('type', true);

            if ($page_model->save()) {
                Functions::alert("<li>اطلاعات ذخیره شد</li>", 'success');
            } else {
                Functions::alert("<li>عدم ذخیره سازی اطلاعات ، دوباره تلاش کنید</li>", 'danger');
            }
            redirect("dashboard/pages/{$this->input->post('type', true)}");
        } else {

            if (empty($type)) {
                redirect('dashboard');
            }
            $page_model = Page_model::where('slug', '=', $type)->first();
            $data['page'] = $page_model;


        }

        $data['title'] = '';
        if ($type == 'about') {
            $data['title'] = 'درباره ما';
        } elseif ($type == 'contact') {
            $data['title'] = 'تماس با ما';
        }
        $script = ['persianslug.js', 'slug.js'];
        $data['script'] = $script;
        $data['type'] = $type;
        $this->load->view('dashboard/page/index', $data);

    }


    public function messages()
    {
        $messages = null;
        if ($this->input->post()) {
            //search
            $keyword = $this->input->post('keyword', true);
            if (empty($keyword)) {
                redirect('dashboard/messages');
            }
            redirect("dashboard/messages/?keyword={$keyword}");

        } else {
            //index

            $page_num = $this->input->get('per_page', true);
            $keyword = $this->input->get('keyword', true);

            $this->load->library('pagination');
            $this->load->model('Message_model');
            $message_model = new Message_model();

            if (!empty($keyword)) {
                //so load search results
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/messages/?keyword={$keyword}"),
                    "rows" => $message_model::where('fullname', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%")
                        ->orWhere('description', 'LIKE', "%{$keyword}%")
                        ->orWhere('subject', 'LIKE', "%{$keyword}%")
                        ->count(),
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE

                ]);
                $config['page_query_string'] = TRUE;
                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];
                $messages = $message_model::where('fullname', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhere('subject', 'LIKE', "%{$keyword}%")
                    ->offset($offset)
                    ->limit($config['per_page'])
                    ->latest()
                    ->get();

            } else {
                //all
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/messages/"),
                    "rows" => $message_model::all()->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE


                ]);
                $config['page_query_string'] = TRUE;
                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];

                $messages = $message_model->offset($offset)->limit($config['per_page'])->latest()->get();

            }
        }

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["messages"] = $messages;
        $data['title'] = 'پیام های دریافتی';
        $this->load->view('dashboard/message/index', $data);
    }

    public function message($message_id = null)
    {
        if (empty($message_id)) {
            redirect('dashboard/messages');
        }

        $this->load->model('Message_model');
        $message = Message_model::find($message_id);
        if (empty($message)) {
            redirect('dashboard/messages');
        }
        $message->status = 'read';
        $message->save();
        $data["message"] = $message;
        $data['title'] = 'پیام های دریافتی';
        $this->load->view('dashboard/message/show', $data);

    }


    public function message_delete($message_id = null)
    {
        if (empty($message_id)) {
            redirect('dashboard/messages');
        }
        $this->load->model('Message_model');
        if (Message_model::destroy($message_id)) {
            Functions::alert("<li>اطلاعات حذف شد</li>", 'success');
        } else {
            Functions::alert("<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>", 'danger');
        }
        redirect('dashboard/messages');


    }


    public function comments()
    {


        $page_num = $this->input->get('per_page', true);
        $this->load->library('pagination');
        $this->load->model('User_model');
        $this->load->model('Post_model');
        $this->load->model('Comment_model');
        $comment_model = new Comment_model();

        //all
        $config = Functions::pagination_config([
            "url" => base_url("dashboard/comments/"),
            "rows" => $comment_model::all()->count(),
            "current" => $page_num,
            "per_page" => $this->per_page,
            'prev_link' => false,
            'next_link' => false,
            'page_query_string' => TRUE
        ]);
        $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
            ($page_num * $config['per_page']) - $config['per_page'];

        $comments = $comment_model->offset($offset)->limit($config['per_page'])->latest()->get();


        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["comments"] = $comments;
        $data['title'] = 'نظرات';
        $this->load->view('dashboard/comment/index', $data);
    }


    public function comment($post_id = null)
    {
        $this->load->model('Post_model');
        $this->load->model('User_model');
        $this->load->model('Comment_model');
        $post = Post_model::find($post_id);
        if (empty($post)) {
            redirect('dashboard/comments');
        }
        // update status to read
        Comment_model::whereStatus('unread')->update(['status' => 'read']);
        $data['post'] = $post;
        $data['comments'] = $post->comments;
        $data['title'] = 'مشاهده نظر';
        $this->load->view('dashboard/comment/show', $data);
    }

    public function comment_delete($comment_id, $post_id)
    {
        if (empty($comment_id) || empty($post_id)) {
            redirect('dashboard/comments');
        }
        $this->load->model('Comment_model');
        if (Comment_model::destroy($comment_id)) {
            Functions::alert('<li>اطلاعات حذف شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>', 'success');
        }
        redirect('dashboard/comment/' . $post_id);
    }

    public function comment_confirm($comment_id, $post_id)
    {
        if (empty($comment_id) || empty($post_id)) {
            redirect('dashboard/comments');
        }
        $this->load->model('Comment_model');
        $comment = Comment_model::find($comment_id);
        if ($comment->status == 'confirm') {
            $comment->status = 'read';
        } else {
            $comment->status = 'confirm';
        }
        if ($comment->save()) {
            Functions::alert('<li>اطلاعات ویرایش شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect('dashboard/comment/' . $post_id);
    }

    public function categories()
    {
        $categories = null;

        if ($this->input->post()) {
            //search
            $keyword = $this->input->post('keyword', true);
            if (empty($keyword)) {
                redirect('dashboard/categories');
            }
            redirect("dashboard/categories/?keyword={$keyword}");

        } else {
            //index

            $page_num = $this->input->get('per_page', true);
            $keyword = $this->input->get('keyword', true);

            $this->load->library('pagination');
            $this->load->model('Category_model');
            $category_model = new Category_model();

            if (!empty($keyword)) {
                //so load search results
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/categories/?keyword={$keyword}"),
                    "rows" => $category_model::where('name', 'LIKE', "%{$keyword}%")->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE
                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];
                $categories = $category_model::where('name', 'LIKE', "%{$keyword}%")
                    ->offset($offset)
                    ->limit($config['per_page'])
                    ->latest()
                    ->get();

            } else {
                //all
                $config = Functions::pagination_config([
                    "url" => base_url("dashboard/categories/"),
                    "rows" => $category_model::all()->count(),
                    "current" => $page_num,
                    "per_page" => $this->per_page,
                    'prev_link' => false,
                    'next_link' => false,
                    'page_query_string' => TRUE

                ]);

                $offset = ($page_num == null) || (!is_numeric($page_num)) ? 0 :
                    ($page_num * $config['per_page']) - $config['per_page'];

                $categories = $category_model->offset($offset)->limit($config['per_page'])->latest()->get();

            }
        }
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data["categories"] = $categories;
        $data['title'] = 'دسته بندی ها';
        $this->load->view('dashboard/category/index', $data);

    }

    public function category_create()
    {
        $this->load->model('Category_model');
        $data['categories'] = Category_model::where('parent_id', 0)->latest()->get();
        $data['title'] = 'افزودن دسته بندی';
        $this->load->view('dashboard/category/create', $data);
    }

    public function category_store()
    {
        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect('dashboard/category_create');
        }
        // ---- Form input validation

        $this->load->model('Category_model');
        $category_model = new Category_model();


        $slug = url_title($this->input->post('name', true));

        //check slug duplication
        while (Category_model::where('slug', '=', $slug)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }

        $category_model->name = $this->input->post('name', true);
        $category_model->slug = $slug;
        $category_model->parent_id = $this->input->post('parent_id', true);


        if ($category_model->save()) {
            Functions::alert('<li>اطلاعات ذخیره شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ذخیره سازی اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect('dashboard/category_create');

    }

    public function category_edit($category_id = null)
    {
        if (empty($category_id)) {
            redirect('dashboard/categories');
        }
        $this->load->model('Category_model');
        $data['category'] = Category_model::find($category_id);
        $data['categories'] = Category_model::where('parent_id', 0)->where('id', '<>', $category_id)->latest()->get();
        $data['title'] = 'ویرایش دسته بندی';
        $this->load->view('dashboard/category/edit', $data);
    }

    public function category_update()
    {

        $category_id = $this->input->post('category_id', true);
        if (empty($category_id)) {
            redirect('dashboard/categories');
        }

        // Form input validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required', array(
            'required' => 'عنوان را وارد نمایید'
        ));

        if ($this->form_validation->run() == false) {
            Functions::alert("<li>{$this->form_validation->error_string()}</li>", 'warning');
            redirect("dashboard/category_edit/{$category_id}");
        }
        // ---- Form input validation

        $this->load->model('Category_model');
        $category_model = Category_model::find($category_id);

        $slug = url_title($this->input->post('name', true));

        //check slug duplication
        while (Category_model::where('slug', '=', $slug)->where('id', '!=', $category_id)->count() > 0) {
            $slug = $slug . '-' . Functions::generateRandomNumber(5);
        }


        $category_model->name = $this->input->post('name', true);
        $category_model->slug = $slug;
        $category_model->parent_id = $this->input->post('parent_id', true);


        if ($category_model->save()) {
            Functions::alert('<li>اطلاعات ویرایش شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم ویرایش اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect("dashboard/category_edit/{$category_id}");
    }

    public function category_delete($category_id = null)
    {
        if (empty($category_id)) {
            redirect("dashboard/categories");
        }
        $this->load->model('Category_model');

        if (Category_model::find($category_id)->parent_id == 0) {
            Category_model::where('parent_id', $category_id)->update(['parent_id' => 0]);
        }

        if (Category_model::destroy($category_id)) {
            Functions::alert('<li>اطلاعات حذف شد</li>', 'success');
        } else {
            Functions::alert('<li>عدم حذف اطلاعات ، دوباره تلاش کنید</li>', 'danger');
        }
        redirect("dashboard/categories");
    }

    private function fetch_files($path)
    {
        $files = array();
        if (file_exists($path)) {
            foreach (new DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDot()) continue;
                $file = $path . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
                $files[] = $file;
            }
        }
        return $files;
    }

    public function files()
    {
        $path = FCPATH . 'uploads';
        $data['path'] = $path;
        $data['files'] = null;
        if (file_exists($path)) {
            $data['files'] = $this->fetch_files($path);
        } else {
            Functions::make_directory($path);
        }

        $style = ['jquery-confirm.min.css'];
        $script = ['jquery-confirm.min.js', 'jquery-ui.js'];
        $data['style'] = $style;
        $data['script'] = $script;
        $data['title'] = 'مدیریت فایل ها';
        $this->load->view('dashboard/file/index', $data);
    }

    public function open_dir()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $path = $this->input->post('path', true);
        $data['path'] = $path;
        $data['files'] = $this->fetch_files($path);

        $view = $this->load->view('dashboard/inc/files', $data, TRUE);
        echo $view;

    }


    public function create_folder()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $path = $this->input->post('path', true);
        $data['path'] = $path;
        $name = $this->input->post('name', true);
        if (empty(trim($name))) {
            echo json_encode(['error' => 'yes', 'text' => 'Enter Folder Name']);
            return;
        }
        if (file_exists($path . DIRECTORY_SEPARATOR . $name)) {
            echo json_encode(['error' => 'yes', 'text' => 'Folder already exists!']);
            return;
        } else {
            Functions::make_directory($path . DIRECTORY_SEPARATOR . $name);
        }

        $data['files'] = $this->fetch_files($path);
        $view = $this->load->view('dashboard/inc/files', $data, TRUE);
        echo json_encode(['error' => 'no', 'text' => $view]);


    }


    public function rename_folder()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }


        $path = $this->input->post('path', true);

        $type = $this->input->post('type', true);
        if (empty(trim($this->input->post('name', true)))) {
            echo json_encode(['error' => 'yes', 'text' => 'Enter Name!']);
            return;
        }
        $old_name = $path;
        $new_name = null;
        switch ($type) {
            case 'file' :
                $old_name = $path;
                $new_name = pathinfo($path, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $this->input->post('name', true) . '.' . pathinfo($path, PATHINFO_EXTENSION);
                break;
            case 'folder' :
                $old_name = $path;
                $new_name = dirname($old_name) . DIRECTORY_SEPARATOR . $this->input->post('name', true);
                break;
        }


        if (file_exists($new_name)) {
            echo json_encode(['error' => 'yes', 'text' => 'Folder already exists!']);
            return;
        }
        $result = rename($old_name, $new_name);
        if ($result && $type == 'file') {
            $path = dirname($new_name);
        } elseif ($result && $type == 'folder') {
            $path = dirname($new_name);
        } else {
            $path = dirname($path);
        }

        $data['path'] = $path;
        $data['files'] = $this->fetch_files($path);
        $view = $this->load->view('dashboard/inc/files', $data, TRUE);
        echo json_encode(['error' => 'no', 'text' => $view]);


    }

    public function file_upload()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $path = $this->input->post('path', true);
        if (empty($path)) {
            echo json_encode(['error' => 'yes', 'text' => 'Path is not valid!']);
            return;
        }
        if (empty($_FILES)) {
            echo json_encode(['error' => 'yes', 'text' => 'Please, Select File!']);
            return;
        }

        Functions::make_directory($path);
        $config = Functions::file_upload_config($path, '*');
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $errors = array('errors' => $this->upload->display_errors());
            echo json_encode(['error' => 'yes', 'text' => implode(',', $errors)]);
            return;
        } else {
            //success
            $data['files'] = $this->fetch_files($path);
            $data['path'] = $path;
            $view = $this->load->view('dashboard/inc/files', $data, TRUE);
            echo json_encode(['error' => 'no', 'text' => $view]);
            return;
        }
    }

    public function file_delete()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $type = $this->input->post('type', true);
        $path = $this->input->post('path', true);
        $file = $this->input->post('file', true);

        if ($type == 'folder') {
            Functions::delDir($file);

        } elseif ($type == 'file') {
            unlink($file);

        }
        //success
        $data['files'] = $this->fetch_files($path);
        $data['path'] = $path;
        $view = $this->load->view('dashboard/inc/files', $data, TRUE);
        echo json_encode(['error' => 'no', 'text' => $view]);
        return;


    }


    public function file_read()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $path = $this->input->post('path', true);
        if (empty(trim($path))) {
            echo json_encode(['error' => 'yes', 'text' => 'Path is not valid']);
            return;
        }

        $data = file_get_contents($path);
        if ($data == false) {
            echo json_encode(['error' => 'yes', 'text' => 'Error on reading file!']);
            return;
        }

        echo json_encode(['error' => 'no', 'text' => $data]);
        return;

    }


    public function file_edit()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $path = $this->input->post('path', true);

        $file_content = $this->input->post('file_content');
        if (empty(trim($path))) {
            echo json_encode(['error' => 'yes', 'text' => 'Path is not valid']);
            return;
        }

        $data = file_put_contents($path, $file_content);
        if ($data == false) {
            echo json_encode(['error' => 'yes', 'text' => 'Error on writing file!']);
            return;
        }
        $data = file_get_contents($path);
        if ($data == false) {
            echo json_encode(['error' => 'yes', 'text' => 'Error on reading file!']);
            return;
        }
        echo json_encode(['error' => 'no', 'text' => $data]);
        return;
    }

    public function file_move()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $current_path = $this->input->post('current_path', true);

        $source_path = $this->input->post('source_path', true);
        $source_type = $this->input->post('source_type', true);

        $target_path = $this->input->post('target_path', true);
        $target_type = $this->input->post('target_type', true);

        if (empty(trim($current_path))) {
            echo json_encode(['error' => 'yes', 'text' => 'Current path is empty!']);
            return;
        }

        if (empty(trim($source_path))) {
            echo json_encode(['error' => 'yes', 'text' => 'Source path is empty!']);
            return;
        }

        if (empty(trim($source_type))) {
            echo json_encode(['error' => 'yes', 'text' => 'Source type is empty!']);
            return;
        }

        if (empty(trim($target_path))) {
            echo json_encode(['error' => 'yes', 'text' => 'Target path is empty!']);
            return;
        }

        if (empty(trim($target_type))) {
            echo json_encode(['error' => 'yes', 'text' => 'Target type is empty!']);
            return;
        }


        if ($target_type == 'folder' && $source_type == 'file') {
            copy($source_path, $target_path . DIRECTORY_SEPARATOR . basename($source_path));
            unlink($source_path);

        } elseif ($target_type == 'folder' && $source_type == 'folder') {
            Functions::copy_directory($source_path, $target_path . DIRECTORY_SEPARATOR . basename($source_path));
            Functions::delDir($source_path);
        }


        $path = $current_path;
        $data['files'] = $this->fetch_files($path);
        $data['path'] = $path;
        $view = $this->load->view('dashboard/inc/files', $data, TRUE);
        echo json_encode(['error' => 'no', 'text' => $view]);
        return;

    }

    public function file_download()
    {
        $path = $this->input->get('path', true);
        if(empty($path)){
            redirect('dashboard/files');
        }
        $this->load->helper('download');
        force_download($path, null);
        return;
        
    }


}