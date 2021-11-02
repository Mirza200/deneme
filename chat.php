<?php
//error_reporting(0);
ob_start();
session_start();
require_once "class/BasicDB.php";
require_once "baglan.php";
require_once "fonksiyon.php";
if ($_SESSION["oturum"]) {

$uye = $db->from('uye')
->where('id', $_SESSION["uyeid"])
->first();

	$tip = strip_tags(post("tip"));
	switch($tip){
		//Mesaj Gönderme
		case "gonder";
		date_default_timezone_set('Europe/Istanbul');
		$mesaj = strip_tags(post("mesaj"));
		$kullanici = $_SESSION["uyeAdi"];
		$rutbe = $_SESSION["rutbe"];
		$tarih = time();
		$saat = date("H:i / d.m.y");
		$krengi = $uye['krengi'];
		$yrengi = $uye['yrengi'];
		$cinsiyet = $uye['cinsiyet'];
		//$cinsiyet == 1 ? 'Erkek' : 'Bayan';
		
		if (empty($mesaj)) {
			echo "bos";
		}else {
			$ac = fopen("chat.txt", "ab");
			$eklenecekler = $tarih."\t".$kullanici."\t".$mesaj."\t".$rutbe."\t".$saat."\t".$krengi."\t".$yrengi."\t".$cinsiyet."\n";
			$ekle = fwrite($ac, $eklenecekler);
		}

		break;

		//Sohbet Güncelleme
		case "guncelle";
		$dosya = file("chat.txt");
		if (empty($dosya)) {
			echo '<div class="alert alert-success" style="margin:0px;" role="alert"><strong>Sohbet Temizlendi!</strong></div>';
		}else{

			$toplam = count($dosya);
			if ($toplam >= 25) {
				unlink("chat.txt");
				touch("chat.txt");
				echo '<div class="alert alert-success" style="margin:0px;" role="alert"><strong>Sohbet Temizlendi!</strong></div>';
				
			}else {
				//arsort($dosya);
				
				foreach ($dosya as $mesaj) {
					
					$bol = explode("\t", $mesaj);
					$linkim = seo($bol[1]);
					echo "<div id='sohbetMesaji'><hr>
					<a href='{$linkim}' class='btn' id='kadi' style='background-color:{$bol[5]}'>
					<span class='badge' style='color:{$bol[5]}'>";
					echo $bol[3] == 1 ? "<span class='glyphicon glyphicon-star'></span>" : "<span class='glyphicon glyphicon-plus'></span>";
					echo "</span> {$bol[1]}";
					echo $bol[7] == 1 ? " <i class='fa fa-mars'></i>" : " <i class='fa fa-venus'></i>";
					echo "</a>
						<span style='color:{$bol[6]};'>{$bol[2]}</span>
						<span class='pull-right' id='tarih'>{$bol[4]}<span>
					</div>";
				}			
			}
		}

		break;

		//Sohbet Temizle
		case "temizle";
		if ($_SESSION["rutbe"] == 1) {
			unlink("chat.txt");
			touch("chat.txt");
			echo "Sohbet Temizlendi";
		}else {
			echo "Sen Yönetici Değilsin Eşşek..";
		}
		break;
	}
}else {
	header('Location:index.php');
}
