<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Auth;
use DateTime;
use Mail;
use App\Post;
use App\EmailSettings;

class Helper
{

    public static function secure_enc( $string, $action = 'e' ) {
        // you may change these values to your own
        $secret_key = 'neacproject';
        $secret_iv = 'neacprojectsecret';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }

        return $output;
    }

    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
          'y' => 'year',
          'm' => 'month',
          'w' => 'week',
          'd' => 'day',
          'h' => 'hour',
          'i' => 'minute',
          's' => 'second',
        );
        foreach ($string as $k => &$v) {
          if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
          } else {
                unset($string[$k]);
          }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function the_excerpt($string, int $limit) {
        if($string != null) {
          $strlen = strlen($string);
          if( $strlen > $limit ) {
            $content = substr($string, 0, $limit).'...';
          } else {
            $content = $string;
          }
          return $content;
        } else {
          return '--';
        }
    }

    public static function str_random($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function api_save_posts($posts, $user_id) {
      foreach($posts as $key => $post) {
        $temp =  array_pad(explode('_fi_', $key),2,null);
        $type = $temp[0];
        $input_id = $temp[1];
        if($input_id) {
            if( $type != '_method' && $type != '_token' ) {
                $select_post =  Post::where('type', $type)
                    ->where('input_id', $input_id)
                    ->where('user_id', $user_id)->first();
                if($type == 'multiple_image' || $type == 'multiple_file') {
                        $files = $posts[$key]['name'];
                        $files_arr = [];
                        for( $i=0 ; $i < count($files) ; $i++ ) {
                            $tmpFilePath = $posts[$key]['tmp_name'][$i];
                            if ($tmpFilePath != ""){
                            $info = pathinfo($posts[$key]['name'][$i]);
                            $base_name =  basename($posts[$key]['name'][$i],'.'.$info['extension']);
                            $ext = pathinfo($posts[$key]['name'][$i], PATHINFO_EXTENSION);
                            $fileName = $base_name .'_nurse_'. time() . '.' .$ext;
                            $newFilePath = public_path("documents") . "\'". $fileName;
                            copy($tmpFilePath, str_replace("'", "", $newFilePath));
                            $files_arr[] = $fileName;
                            }
                        } 
                        $post = implode(',', $files_arr); 
                
                } else if($type == 'checkbox') {
                    $post = implode(',', $post);
                }
                if(empty($select_post)) {
                    // Insert POST
                    $post_save = new Post;
                    $post_save->user_id = $user_id;
                    $post_save->input_id = $input_id;
                    $post_save->type = $type;
                    $post_save->post = $post;
                    $post_save->save();
                } else {
                    $post_update = Post::find($select_post->id);
                    $post_update->user_id = $user_id;
                    $post_update->input_id = $input_id;
                    $post_update->type = $type;
                    $post_update->post = $post;
                    $post_update->update();
                }
            }    
        }
      }
    }


    public static function mail_formatter($email_settings_id, $user, $others) {

        $email_settings = EmailSettings::find($email_settings_id);

        $vars = array(
            '{$application_number}' => (isset($user->profile)) ? $user->profile->application_number : '',
            '{$employee_number}' => (isset($user->employee)) ? $user->employee->employee_number : '',
            '{$first_name}' => $user->first_name,
            '{$last_name}' => $user->last_name,
            '{$email}' => $user->email,
            '{$password}' => (isset($others['password'])) ? $others['password'] : '',
            '{$application_status_status_message}' =>  (isset($others['application_status_status_message'])) ? $others['application_status_status_message'] : '',
            '{$application_status}' =>  (isset($others['application_status'])) ? $others['application_status'] : '',
            '{$form}' => (isset($others['form'])) ? $others['form'] : '',
            '{$email_register_validation_portal_link}' => (isset($others['email_register_validation_portal_link'])) ? $others['email_register_validation_portal_link'] : '',
            '{$email_reset_password_portal_link}' => (isset($others['email_reset_password_portal_link'])) ? $others['email_reset_password_portal_link'] : '',
            '{$company}' => (isset($others['company'])) ? $others['company'] : '',
            '{$reseller_code}' => (isset($others['reseller_code'])) ? $others['reseller_code'] : '',
        );

        $mail_header = strtr($email_settings->header, $vars);
        $mail_body = strtr($email_settings->body, $vars);
        $mail_footer = strtr($email_settings->footer, $vars);
        $mail_subject = strtr($email_settings->subject_mail, $vars);

        $to[] = trim($user->email);
        $to[] = (isset($others['email'])) ? trim($others['email']) : '';
        
        if($vars['{$form}'] == 'NCLEX') {
          $to[] = 'partanduls@gmail.com';
        }


        $bcc = [];
        $bcc[] = (isset($others['consultant'])) ? trim($others['consultant']) : '';
        $to_mail = explode(',', $email_settings->to_mail);
        if($to_mail) {
          foreach($to_mail as $item) {
            $bcc[] = trim($item);
          }
        }

        $cc = $email_settings->cc_mail;
        $data = [
            'header'        => $mail_header,
            'body'          => $mail_body,
            'footer'        => $mail_footer,

            'full_name'     => $user->first_name .' '. $user->last_name,
            'to'            => array_filter($to),
            'cc'            => array_filter( (explode(',', $cc) )),
            'bcc'           => array_filter($bcc),
            'attachments'   => (isset($others['attachments'])) ? implode(',', $others['attachments']) : '',
            'subject'       => $mail_subject
        ];
        Mail::send('mails.mail', $data, function($message) use ($data) {
            $message->to($data['to'], $data['full_name'])->subject($data['subject']);
            $message->from('support@applynclex.com','NEAC Medical Exams Center');
            if($data['cc']) {
                $message->cc($data['cc']);
            }
            if($data['bcc']) {
              $message->bcc($data['bcc']);
            }
            if($data['attachments']) {
                $message->attach($data['attachments']);
            }
        });
        return (count(Mail::failures()) > 0 ) ? false : true;
    }

}
