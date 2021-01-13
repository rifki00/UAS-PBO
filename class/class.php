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



}

?>