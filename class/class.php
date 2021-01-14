<?php
	require 'class_koneksi.php';

class Pengguna
{

    public function __construct()
    {
        $this->db = new Db();
	}

	public function Login($username, $password)
	{
		$username = $this->db->antisqlinjection($username);
		$password = $this->db->antisqlinjection($password);

		if($this->db->tersedia("pengguna", "username", $username) || $this->db->tersedia("pengguna", "email", $username))
		{
			$hasil = $this->db->select("SELECT * FROM `pengguna` WHERE `username` = '$username' OR `email` = '$username'");
			if($hasil[0]['password'] == $password)
			{
				$_SESSION['logged']   = true;
				$this->db->query("INSERT INTO `log_login` (`id`, `id_pengguna`, `ip`, `waktu`, `perangkat_lunak`, `perangkat_keras`) VALUES (NULL, '".$hasil[0]['id']."', '".$this->DatapatkanIP()."', '".date("Y-m-d H:i:s")."', '".$this->DapatkanPerangkatUser()."', '".$this->DapatuserOS()."');");

				echo "<script>alert('Sukses Login');</script><script>window.history.back()</script>"; 
			}
			else
			{
				echo "<script>alert('Terjadi kesalahan!');</script><script>window.history.back()</script>"; 
			}
		}
		else
		{
			echo "<script>alert('(nama karater / email) atau Password Salah!!!.');</script><script>window.history.back()</script>"; 
			return true;
		}
		return false;
	}

	public function Daftar($username, $email, $password, $repassword, $kelamin)
	{
		$username    = $this->db->antisqlinjection($username);
		$email       = $this->db->antisqlinjection($email);
		$password    = $this->db->antisqlinjection($password);
		$repassword  = $this->db->antisqlinjection($repassword);
		$kelamin 	 = $this->db->antisqlinjection($kelamin);

		if(!$this->db->tersedia("pengguna", "username", $username))
		{
			if(!$this->db->tersedia("pengguna", "email", $email))
			{
				
				if(strlen($password) < 8)
				{
					@$kesalahan = true;
					echo "<script>alert('Password harus minimal 8 karakter');</script><script>window.history.back()</script>"; 
				}
			
				if($password != $repassword)
				{
					@$kesalahan = true;
					echo "<script>alert('Password dan Re-type Password tidak sama!');</script><script>window.history.back()</script>"; 
				}	
				if(!@$kesalahan)
				{
					$result = $this->db->query("INSERT INTO `pengguna` (`id`, `username`, `password`, `kelamin`, `email`, `tanggal_daftar`, `terakhir_login`, `ip_terakhir_login`) VALUES (NULL, '".$username."', '".$password."', '".$kelamin."', '".$email."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '".$this->DatapatkanIP()."');");

					if($result)
					{
						echo "<script>alert('Akun kamu berhasil di buat silahkan login!');</script><script>window.location.href = '/index.php';</script>";
					}
					else
					{
						echo "<script>alert('Terjadi kesalahan saat membuat akun!');</script><script>window.history.back()</script>";
					}
				}
			}
			else
			{
				echo "<script>alert('Alamat E-mail yang kamu input sudah di gunakan oleh orang lain!');</script><script>window.history.back()</script>";
			}
		}
		else
		{
			echo "<script>alert('Nama karakter ini sudah di gunakan oleh orang lain!');</script><script>window.history.back()</script>"; 
		}
	}


	public function DatapatkanIP() 
	{
		$ip = $_SERVER['REMOTE_ADDR'];	
		return $ip;
	}

	public function Urlwebnya()
	{
		$fullurl = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		return $fullurl;
	}

	public function DapatkanPerangkatUser()
	{
		return $_SERVER["HTTP_USER_AGENT"];
	}


	public function url($url, $timeout)
	{
		//mengambil data menggunakan curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	} 

	public function DapatuserOS()
	{
		$useragent = $_SERVER["HTTP_USER_AGENT"];
		$user_load = $this->url("http://www.useragentstring.com/?uas=".urlencode($useragent)."&getJSON=all", 5);
		$user_load = json_decode($user_load);
		$os = $user_load->os_type." (".$user_load->os_name.")";
		return $os;
	}


}

?>