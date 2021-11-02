<?php
require_once "class/BasicDB.php";
require_once "baglan.php";
require_once "class/class.upload.php";
require_once "fonksiyon.php";
ob_start();
echo Session();
if (session()) {
	$uye = $db->from('uye')
	->where('id', $_SESSION["uyeid"])
	->run(true);
}
?>
<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<?php if (!isset($_SESSION['oturum']) == false) { ?>
		<title>Sohbet Anasayfa</title>
		<?php } ?>
		<title>Giriş Yap</title>
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//fonts.googleapis.com/css?family=Hammersmith+One|Kalam" rel="stylesheet">
		
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<script type="text/javascript" src="js/sohbet.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/input.css">
	</head>
	<body>
	<div class="container-fluid">
		<?php if (!isset($_SESSION['oturum']) == false) { ?>
		<?php if ($_SESSION['onay'] == 1) { ?>
		<div class="col-lg-10">
			<div id="sohbetIcerik"></div>
			<div id="mesajGonder">
				<textarea rows="0" cols="0" class="form-control" name="mesaj" placeholder="Yazmaya başla..." autofocus></textarea>
			</div>
		</div>
		<div class="col-lg-2">
			<?php online(); ?>

		</div>
		

		
		<!-- Modal -->
		<div class="modal fade" id="profil" role="dialog">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">
		    <div class="modal-header">
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		      <h4 class="modal-title">Profilini Düzenle</h4>
		    </div>
		    <div class="modal-body">
		    <?php include 'profil2.php'; ?>
		    </div>
		    <div class="modal-footer">
		      <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
		    </div>
		  </div>
		</div>
		</div>

		</div>
			<?php }else {
				echo '<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">';
				echo Uyari('Üyeliğiniz Henüz Onaylanmamış!');
				echo '</div></div>';
			}
		}else { ?>
		<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="panel panel-default">
			  <div class="panel-body">
          <center><img width="100" src="logo.png"></center>
			  <h2 class="text-center">Giriş Yap</h2>
			<?php 
			if (isset($_POST['giris'])) {
				$uyeAdi = strip_tags(trim($_POST["kadi"]));
				$sifre = strip_tags(trim($_POST["sifre"]));
				$bul = $db->from('uye')
				->where('uyeAdi', $uyeAdi)
				->where('sifre', $sifre)
				->first();
				//print_r($bul);
				   
				if ( $bul ){
					$_SESSION["oturum"] = true;
					$_SESSION["uyeAdi"] = $uyeAdi;
					$_SESSION["uyeid"] = $bul["id"];
					$_SESSION["rutbe"] = $bul["rutbe"];
					$_SESSION["onay"] = $bul["onay"];
					header("Location:index.php");
				}else {
					echo Hata('Giriş Başarısız!');
				}
			}
			?>
				<form action="" method="post">
					<div class="form-group">
					<input type="text" name="kadi" placeholder="Kullanıcı Adı" class="form-control">
					</div>
					<div class="form-group">
					<input type="password" name="sifre" placeholder="Şifre" class="form-control" class="giris_in">
					</div>
					<input type="submit" name="giris" class="btn btn-primary btn-block" value="Giriş Yap">
					<a href="uye-ol.php" class="btn btn-success btn-block">Kayıt Ol</a>					
				</form>
			  </div>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php AltMenu(); ?>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
