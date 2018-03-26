<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Functions
{

    protected static $CI;
    private static $temp = null;

    public function __construct()
    {
        self::$CI =& get_instance();
    }

    public static function old($key = null, $value = null)
    {
        self::$CI->load->library('session');
        if (!empty(trim($key))) {
            if (empty(trim($value))) $value = '';
            self::$CI->session->set_flashdata($key, $value);
        }
    }

    public static function read($key = null)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }

    /* alert functions per request http verbs*/
    public static function alert($message = "", $status = "info")
    {
        self::$CI->load->library('session');
        self::$CI->session->set_flashdata('message', $message);
        self::$CI->session->set_flashdata('status', $status);
    }

    public static function file_upload_config($path, $file_types = 'gif|png|jpg', $file_size = '2048')
    {
        $config['upload_path'] = $path;
        $config['allowed_types'] = $file_types;
        $config['max_size'] = $file_size;
        $config['max_width'] = 0;
        $config['max_height'] = 0;
        $config['encrypt_name'] = TRUE;
        $config['remove_spaces'] = TRUE;
        return $config;
    }

    public static function seo_url($string)
    {
        $string = str_replace(' ', '-', $string);
        $string = stripslashes($string);
        return htmlspecialchars($string);
    }

    public static function create_slug($table, $slug, $column = "slug")
    {

        $result = array();
        $i = 1;
        self::$CI->db->select($column);
        self::$CI->db->from($table);
        $slugs = self::$CI->db->get()->result_array();

        foreach ($slugs as $row) {
            if ($row[$column] == $slug) {
                $slug = $slug . '-' . $i;
                $i++;
            }
        }


        return $slug;


    }

    public static function now()
    {
        $now = date('Y-m-d H:i:s');
        return $now;
    }


    public static function pagination_config(array $page_config = null)
    {
        $config['base_url'] = isset($page_config['url']) ? $page_config['url'] : null;
        $config['total_rows'] = isset($page_config['rows']) ? $page_config['rows'] : null;
        $config['per_page'] = isset($page_config['per_page']) ? $page_config['per_page'] : 20;
        $config['uri_segment'] = isset($page_config['segment']) ? $page_config['segment'] : 3;
        $config['use_page_numbers'] = isset($page_config['use_page_numbers']) ? $page_config['use_page_numbers'] : true;
        $config['cur_page'] = $page_config['current'];
        $config['num_links'] = isset($page_config['num_links']) ? $page_config['num_links'] : 5;
        $config['page_query_string'] = isset($page_config['page_query_string']) ? $page_config['page_query_string'] : false;
        //config for bootstrap pagination class integration
        $config['first_link'] = isset($page_config['first_link']) ? $page_config['first_link'] : '&laquo;';
        $config['last_link'] = isset($page_config['last_link']) ? $page_config['last_link'] : '&raquo;';
        $config['prev_link'] = isset($page_config['prev_link']) ? $page_config['prev_link'] : ' &rarr; قبلی';
        $config['next_link'] = isset($page_config['next_link']) ? $page_config['next_link'] : ' بعدی &larr;';
        // -----
        $config['full_tag_open'] = isset($page_config['full_tag_open']) ? $page_config['full_tag_open'] : '<ul class="pagination">';
        $config['full_tag_close'] = isset($page_config['full_tag_close']) ? $page_config['full_tag_close'] : '</ul>';

        $config['first_tag_open'] = isset($page_config['first_tag_open']) ? $page_config['first_tag_open'] : '<li>';
        $config['first_tag_close'] = isset($page_config['first_tag_close']) ? $page_config['first_tag_close'] : '</li>';
        $config['prev_tag_open'] = isset($page_config['prev_tag_open']) ? $page_config['prev_tag_open'] : '<li class="prev">';
        $config['prev_tag_close'] = isset($page_config['prev_tag_close']) ? $page_config['prev_tag_close'] : '</li>';

        $config['next_tag_open'] = isset($page_config['next_tag_open']) ? $page_config['next_tag_open'] : '<li>';
        $config['next_tag_close'] = isset($page_config['next_tag_close']) ? $page_config['next_tag_close'] : '</li>';
        $config['last_tag_open'] = isset($page_config['last_tag_open']) ? $page_config['last_tag_open'] : '<li>';
        $config['last_tag_close'] = isset($page_config['last_tag_close']) ? $page_config['last_tag_close'] : '</li>';
        $config['cur_tag_open'] = isset($page_config['cur_tag_open']) ? $page_config['cur_tag_open'] : '<li class="active"><a href="#">';
        $config['cur_tag_close'] = isset($page_config['cur_tag_close']) ? $page_config['cur_tag_close'] : '</a></li>';
        $config['num_tag_open'] = isset($page_config['num_tag_open']) ? $page_config['num_tag_open'] : '<li>';
        $config['num_tag_close'] = isset($page_config['num_tag_close']) ? $page_config['num_tag_close'] : '</li>';
        return $config;
    }


    public static function jalali_to_gregorian($date)
    {
        self::$CI->load->library('jdf');
        $date = explode('/', $date);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        $e = self::$CI->jdf->jalali_to_gregorian($year, $month, $day, '-');
        return date('Y-m-d', strtotime($e));
    }


    public static function dd($object, $preview = false)
    {
        if ($preview) {
            echo '<pre>';
        }
        var_dump($object);
        die();
    }


    public static function generateRandomString($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public static function generateRandomNumber($length = 10)
    {
        return substr(str_shuffle(str_repeat("0123456789", ($length / 2))), 0, $length);
    }


    /**
     * shortens the supplied text after last word
     * @param string $string
     * @param int $max_length
     * @param string $end_substitute text to append, for example "..."
     * @param boolean $html_linebreaks if LF entities should be converted to <br />
     * @return string
     */
    public static function mb_word_wrap($string, $max_length, $end_substitute = null, $html_linebreaks = true)
    {

        if ($html_linebreaks) $string = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
        $string = strip_tags($string); //gets rid of the HTML

        if (empty($string) || mb_strlen($string) <= $max_length) {
            if ($html_linebreaks) $string = nl2br($string);
            return $string;
        }

        if ($end_substitute) $max_length -= mb_strlen($end_substitute, 'UTF-8');

        $stack_count = 0;
        while ($max_length > 0) {
            $char = mb_substr($string, --$max_length, 1, 'UTF-8');
            if (preg_match('#[^\p{L}\p{N}]#iu', $char)) $stack_count++; //only alnum characters
            elseif ($stack_count > 0) {
                $max_length++;
                break;
            }
        }
        $string = mb_substr($string, 0, $max_length, 'UTF-8') . $end_substitute;
        if ($html_linebreaks) $string = nl2br($string);

        return $string;
    }


    public static function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'سال',
            'm' => 'ماه',
            'w' => 'هفته',
            'd' => 'روز',
            'h' => 'ساعت',
            'i' => 'دقیقه',
            's' => 'ثانیه',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' پیش' : 'هم اکنون';
    }

    public static function make_directory($path, $mode = 0777, $recursive = true)
    {
        if (!is_dir($path) && !file_exists($path)) {
            mkdir($path, $mode, $recursive);
        }
    }

    public static function delDir($directory)
    {// Custom function recursive function entire directory
        if (file_exists($directory)) {// To determine whether the directory exists ， If there is no rmdir() Function will go wrong
            if ($dir_handle = @opendir($directory)) {// Open directory return directory resource ， And judge whether it is successful
                while ($filename = readdir($dir_handle)) {// List Folder Contents ， Read file or folder in directory
                    if ($filename != '.' && $filename != '..') {// Be sure to exclude two special directories
                        $subFile = $directory . "/" . $filename;// Connect the directory file to the current directory
                        if (is_dir($subFile)) {// If the directory condition becomes
                            Functions::delDir($subFile);// Recursive call to delete subdirectories
                        }
                        if (is_file($subFile)) {// If the file condition is established
                            unlink($subFile);// Delete this file directly
                        }
                    }
                }
                closedir($dir_handle);// Close directory resource
                rmdir($directory);// remove empty directories
            }
        }
    }

    public static function file_mime_type($file)
    {

        $mime_type = mime_content_type($file);
        switch ($mime_type) {
            case 'image' :
                return 'fa-file-image-o';
                break;
            case 'audio' :
                return 'fa-file-audio-o';
                break;
            case 'video' :
                return 'fa-file-video-o';
                break;
            case 'application/pdf' :
                return 'fa-file-pdf-o';
                break;
            case 'text/plain' :
                return 'fa-file-text-o';
                break;
            case 'text/html' :
                return 'fa-file-code-o';
                break;
            case 'application/json' :
                return 'fa-file-code-o';
                break;
            case 'application/gzip' :
                return 'fa-file-archive-o';
                break;
            case 'application/zip' :
                return 'fa-file-archive-o';
                break;
            default :
                return 'fa-file-o';
                break;
        }
    }

    public static function file_size($file)
    {
        $size = filesize($file);
        return static::format_file_size($size);
    }


    public static function directory_size($path)
    {
        $size = 0;
        $dir = opendir($path);
        if (!$dir)
            return -1;
        while (($file = readdir($dir)) !== false) {
            // Skip file pointers
            if ($file[0] == '.') continue;
            // Go recursive down, or add the file size
            if (is_dir($path . $file))
                $size += static::directory_size($path . $file . DIRECTORY_SEPARATOR);
            else
                $size += filesize($path . $file);
        }
        closedir($dir);
        return static::format_file_size($size);
    }

    public static function format_file_size($size)
    {
        $mod = 1024;
        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }
        return round($size, 2) . ' ' . $units[$i];
    }


    public static function copy_directory($source, $destination)
    {
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (FALSE !== ($readdirectory = $directory->read())) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }
                $PathDir = $source . DIRECTORY_SEPARATOR . $readdirectory;
                if (is_dir($PathDir)) {
                    static::copy_directory($PathDir, $destination . DIRECTORY_SEPARATOR . $readdirectory);
                    continue;
                }
                copy($PathDir, $destination . DIRECTORY_SEPARATOR . $readdirectory);
            }

            $directory->close();
        }

    }



}