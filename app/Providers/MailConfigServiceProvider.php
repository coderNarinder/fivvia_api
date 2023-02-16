<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{ClientPreference,Client};
use Config;
use DB;
use Illuminate\Http\Request;
class MailConfigServiceProvider extends ServiceProvider
{
/**
* Bootstrap services.
*
* @return void
*/
public function boot(Request $request)
{

// $clientID = getWebClientID();
//  // dd($clientID);
// $mail = ClientPreference::select(['id', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from'])
// ->where('client_id',$clientID)->first();
 

// 	$mail_driver = !empty($mail->mail_driver) ? $mail->mail_driver : env('MAIL_DRIVER');
// 	$mail_host = !empty($mail->mail_host) ? $mail->mail_host : env('MAIL_HOST');

// 	$mail_port = !empty($mail->mail_port) ? $mail->mail_port : env('MAIL_PORT');
// 	$mail_from = !empty($mail->mail_from) ? $mail->mail_from : env('MAIL_FROM_NAME');
// 	$mail_encryption = !empty($mail->mail_encryption) ? $mail->mail_encryption : env('MAIL_ENCRYPTION');
// 	$mail_username = !empty($mail->mail_username) ? $mail->mail_username : env('MAIL_USERNAME');
// 	$mail_password = !empty($mail->mail_password) ? $mail->mail_password : env('MAIL_PASSWORD'); 

// 	 if (isset($mail->id)){
// 		$config = array(
// 			'driver' => $mail_driver,
// 			'host' => $mail_host,
// 			'port' => $mail_port,
// 			'from' => array('address' => $mail_from, 'name' => $mail_from),
// 			'encryption' => $mail_encryption,
// 			'username' => $mail_username,
// 			'password' => $mail_password,
// 			'sendmail' => '/usr/sbin/sendmail -bs',
// 			'pretend' => false
// 			);
// 	        Config::set('mail', $config); 
// 	 }
 
}
/**
* Register services.
*
* @return void
*/
public function register()
{

}


}