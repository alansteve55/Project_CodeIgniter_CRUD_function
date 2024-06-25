<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form','CIMail','CIFunctions'];
    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
     

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required.',
                        'valid_email' => 'please check the email field.',
                        'is_not_unique' => 'Email is not exists.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have atleast 5 characters in length.',
                        'max_length' => 'Password must not have more than 45 characters in length.'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required.',
                        'is_not_unique' => 'Username is not exists.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'Password is required.',
                        'min_length' => 'Password must have atleast 5 characters in length.',
                        'max_length' => 'Password must not have more than 45 characters in length.'
                    ]
                ]
            ]);
        }

        if(!$isValid){
            return view('backend/pages/auth/login',[
                'pageTiltle'=>'Login',
                'validation'=>$this->validator
            ]);
        }else{
            $user = new User();
            $userInfo = $user->where($fieldType,$this->request->getVar('login_id'))->first();
            $check_password = Hash::check($this->request->getVar('password'), $userInfo['password']);

            if(!$check_password){
                return redirect()->route('admin.login.form')->with('fail','Wrong Password')->withInput();
            }else{
                CIAuth::setCIAuth($userInfo); // important line
                return redirect()->route('admin.home');
            }
        }
    }

    public function forgotForm(){
        $data= array(
            'pageTitle'=> 'Forgot password',
            'validation'=> null,
        );
        return view('backend/pages/auth/forgot',$data);
    }

    public function sendPasswordResetLink(){
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'please check the email field.',
                    'is_not_unique' => 'Email is not exists.'
                ]
            ]
        ]);

        if(!$isValid){
            return view('backend/pages/auth/forgot',[
                'pageTitle'=>'Forgot Password',
                'validation'=>$this->validator,
            ]);
        }else{
            echo 'Email sent'; 
            /*Get user (admin) details
            $user = new User();
            $user_info = $user->asObject()->where('email',$this->request->getVar('email'))->first();

            //Generate tokens
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            //Get reset password token
            $password_rest_token = new PasswordResetToken();
            $isOldTokenexists = $password_rest_token->asObject()->where('email',$user_info->email)->first();

            if($isOldTokenexists){
                //update existing token
                $password_rest_token->where('email',$user_info->email)
                                    ->set(['token'=>$token,'created_at'=>Carbon::now()])
                                    ->update(); 
            }else{
                $password_rest_token->insert([
                    'email'=>$user_info->email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);
            }

            //create action link
            $actionLink =route_to('admin.reset-password',$token);

            $mail_data =array(
                'actionLink' => $actionLink,
                'user' => $user_info,
            );

            $view = \Config\Services::renderer();
            $mail_body = $view->setVar('mail_data',$mail_data)->render('email-templates/forgot-email-template');

                $mailConfig = array(
                    'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name'=>env('EMAIL_FROM_NAME'),
                    'mail_recipient_email'=>$user_info->email,
                    'mail_recipient_name'=>$user_info->name,
                    'mail_subject'=>'Reset Password',
                    'mail_body'=>$mail_body
                );

                //send email
                if(sendemail($mailConfig)){
                    return redirect()->route('admin.forgot.form')->with('success','We have sent an email regarding password rest link.');
                }else{
                    return redirect()->route('admin.forgot.form')->with('fail','Something went wrong.');
                }*/
        }
    }
}
