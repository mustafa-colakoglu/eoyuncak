<?php
	namespace App\Models;
	use System\Libraries\Model;
	use System\Libraries\Config;
	class IOModel extends Model{

		function login($username,$password){

			$password = md5($password);

			if(strstr($username, "@")){

				$user = $this->db->query("SELECT * FROM uyeler WHERE Eposta='$username' AND Parola='$password'");
			}else{

				$user = $this->db->query("SELECT * FROM uyeler WHERE KullaniciAdi='$username' AND Parola='$password'");
			}

			


			if(count($user)>0){
				foreach ($user as $u) {
					$_SESSION['userId'] = $u->UyeId;
					$_SESSION['rolId'] = $u->RolId;
					$_SESSION['userName'] = $username;
					$_SESSION["md5"] = md5($u->UyeId.$u->RolId.$username);
				}
				return [
					'error' => 0,
					'errormessage' => "Giriş Başarılı"
				];
			}else{

				return [
					'error' => 1,
					'errorMessage' => "Kullanıcı adı veya parola hatalı !"
				];
			}
			

		}
		function adminLogin($username,$password){
			$adminFind = $this->db->table("uyeler")->select("UyeId")->where("KullaniciAdi",$username)->where("Parola",md5($password))->where("RolId",1)->get();
			/*echo "<pre>";
			var_dump($adminFind);
			exit;*/
			if(count($adminFind) > 0){
				$_SESSION['userId'] = $adminFind->UyeId;
				$_SESSION['rolId'] = $adminFind->RolId;
				$_SESSION['userName'] = $username;
				$_SESSION["md5"] = md5($u->UyeId.$u->RolId.$username);
				$_SESSION["adminLogin"] = true;
				return true;
			}
			return false;
		}
		function logout(){

			unset($_SESSION["userId"]);
			unset($_SESSION["rolId"]);
			unset($_SESSION["userName"]);
			unset($_SESSION["md5"]);
			unset($_SESSION["adminLogin"]);

		}

		function isLogin(){

			if(isset($_SESSION['md5']) && isset($_SESSION['userId']) &&isset($_SESSION['rolId']) && isset($_SESSION['userName'])){
				
				if($_SESSION['md5'] == md5($_SESSION['userId'].$_SESSION['rolId'].$_SESSION['userName'])){

						return true;
					}
			}
			return false;
		}
		function getId(){
			return $_SESSION["userId"];
		}
	}
?>