<?php
error_reporting(0);
ob_start();
session_start();

include "class/BasicDB.php";
include "baglan.php";
include "fonksiyon.php";
?>
<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Kayıt Ol - Sohbet</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//fonts.googleapis.com/css?family=Hammersmith+One|Kalam" rel="stylesheet">
		<script type="text/javascript" src="//code.jquery.com/jquery-1.5.2.min.js"></script>

		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script type="text/javascript">
		function isValid(frm)
		{
		    var kadi = frm.kadi.value;
		    var sifre1 = frm.sifre1.value;
		    var sifre2 = frm.sifre2.value;
		    var cinsiyet = frm.cinsiyet.value;

		    if ( kadi==null || kadi=="" || kadi.length < 4 )
		    {
		        alert("Kullanıcı adı 4 karakterden az olamaz");
		        return false;
		    }
		    if ( sifre1 == null || sifre1 == "" || sifre2 == null || sifre2 == "")
		    {
		        alert("Şifreyi boş bırakmayın");
		        return false;
		    }
		    if ( !(sifre1 == sifre2) )
		    {
		        alert("Şifreler eşleşmiyor");
		        return false;
		    }
		    if ( cinsiyet == null || cinsiyet == "" )
		    {
		        alert("Cinsiyet Seçmediniz");
		        return false;
		    }
    
		    return true;
		}
		</script>
		<script type="text/javascript">
			$(function(){
				$("select[name=il]").change(function(){
					$("select[name=ilce]").remove();
					var Id = $(this).val();
					if (Id !=0) {
						$.post("ajax.php",{"id":Id}, function(ilsonuc){
							$("select[name=il]").after('<p><div class="form-group"><select name="ilce" class="form-control"></select></div></p>');
							$("select[name=ilce").html(ilsonuc);
						});
					}
				});
			});
		</script>	

	</head>
	<body>
	<div class="container">
		<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3"><br><br><br>
			<div class="panel panel-default">
			  <div class="panel-body">
				<?php
				$bul = $db->from('uye')
				->where('uyeAdi', post('kadi'))
				->first();
				if ($bul) {
					echo '<p class="btn btn-danger btn-block">Hadiyaa Tüh! Bu isimde kullanıcı var.</p>';
				}

				$iller = $db->from('iller')
				->where('id', post('il'))
				->first();

				$ilceler = $db->from('ilceler')
				->where('id', post('kadi'))
				->first();

				if ($_POST['uyeekle']) {
					$query = $db->insert('uye')
					->set(array(
						'uyeAdi' => post('kadi')),
						'seoadi' => seo(post('kadi')),
						'sifre' => trim(post('sifre1')),
						'rutbe' => 0,
						'hakkinda' => 'Düzenlenmedi.',
						'krengi' => '#2c3e50',
						'yrengi' => '#34495e',
						'cinsiyet' => post('cinsiyet'),
						'il' => $iller['adi'],
						'onay' => 1,
						'ilce' => $ilceler['adi'],
						'sure' => time()
					));
				}
				   
				if ( $query ){
				  echo '<p class="btn btn-success btn-block">Tebrikler! Başarıyla Kaydoldunuz.</p>';
				  header("Refresh: 2;url=index.php");
				}else {	?>

			  <h2 class="text-center">Kaydol</h2>
				<form action="" name="uye_formu" method="post" onsubmit="return isValid(this)">
					<div class="form-group">
					<input type="text" name="kadi" placeholder="Kullanıcı Adı" class="form-control" >
					</div>
					<div class="form-group">
					<input type="password" name="sifre1" placeholder="Şifre Belirle" class="form-control" >
					</div>
					<div class="form-group">
					<input type="password" name="sifre2" placeholder="Şifre Tekrarı" class="form-control" >
					</div>
					<div class="form-group">
					<select style="color:#999;" name="cinsiyet" class="form-control">
						<option hidden selected value="">Cinsiyet Seçimi</option>
						<option style="color:black;" value="1">Erkek</option>
						<option style="color:black;" value="2">Bayan</option>
					</select>
					</div>
			        <div class="form-group">
						<select style="color:#999;" name="il" class="form-control">
						<option value="0">İl Seçin</option>
						<?php
						$iller = $db->from('iller')
						->orderby('adi', 'asc')
						->all();
						foreach ( $iller as $il ){
						echo '<option value="'.$il['id'].'">'.$il['adi'].'</option>';
						}
						?>	
						</select>
					</div>		    	
					<input type="submit" name="uyeekle" class="btn btn-success btn-block" value="Kaydı Tamamla">
					<a href="index.php" class="btn btn-primary btn-block">Giriş Yap</a>					
				</form>
				<?php } ?>
			  </div>
			</div>
		</div>
	</div>


		<!-- jQuery -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>