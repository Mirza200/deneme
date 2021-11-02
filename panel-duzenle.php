<?php
error_reporting(0);
ob_start();
session_start();
include "class/BasicDB.php";
include "baglan.php";
include "class/class.upload.php";
include "fonksiyon.php";
?>
<?php if (@$_SESSION["oturum"] and $_SESSION["rutbe"] == 1) { ?>
<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Sohbet</title>
		<!-- Bootstrap CSS -->
		<script type="text/javascript" language="javascript" src="js/jquery-3.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//fonts.googleapis.com/css?family=Hammersmith+One|Kalam" rel="stylesheet">
		<script type="text/javascript" language="javascript" src="sweetalert/sweetalert.min.js"></script>
    	<link rel="stylesheet" href="sweetalert/sweetalert.css">
		<script type="text/javascript" src="js/hamburger.js"></script>
		<link rel="stylesheet" type="text/css" href="css/hamburger.css">
		<style type="text/css">
			@import url("//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
			body {background-color: #2980b9;}
			.sweet-alert button.cancel{
	  		background-color: #3498db;
	  		color: white;
			}         
		</style>

	</head>
	<body>
    <div id="wrapper">
        <div class="overlay"></div>
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="index.php">
                       Sohbet
                    </a>
                </li>            
                <li>
                    <a href="index.php">Anasayfa</a>
                </li>
                <li>
                    <a href="timeline.php">Durum Paylaş</a>
                </li>
                <?php echo $_SESSION['rutbe'] == 1 ? '<li><a href="panel.php">Yönetim Paneli</a></li>' : ''; ?>
                <li>
                    <a href="cikis.php">Çıkış</a>
                </li>
            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                <span class="hamb-top"></span>
    			<span class="hamb-middle"></span>
				<span class="hamb-bottom"></span>
            </button>
			<div class="container">
				<div class="col-lg-12" style="padding-top:60px">
					<div id="kaybol" class="panel panel-default">
						<div class="panel-body">
			        		<h3><i class="fa fa-gears text-primary"></i> Panel</h3>
			        		<p>Merhaba <strong style="text-transform:capitalize;"><?=$_SESSION["uyeAdi"]?></strong>, Onaylı olmayan üyeler sohbette yazamazlar ve sohbeti göremezler.</p>
			        		<p>Yönetici üyeler bu panelde bulunan tüm işlemleri yapabilmekle beraber, sohbette mesajları temizleyebilirler.</p>
			        	</div>
					</div>
			    </div>

		        <?php
		        if (post('guncelle')) {

		            $uye = $db->update('uye')
		            ->where('id', post('id'))
		            ->set(array(
		             'uyeAdi'    => post('kadi'),
		             'seoadi'    => seo(post('kadi')),
		             'sifre'    => post('sifre'),
		             'rutbe'    => post('rutbe'),
		             'onay'    => post('onay')
		            ));
		               
		            if ($uye){

		                echo '<script>swal({
		                  title: "Başarılı",
		                  text: "<b>'.post('kadi').'</b> Adlı Üye Güncellendi",
		                  type: "success",
		                  timer: 2000,
		                  html: "true",
		                  showConfirmButton: false
		                });</script>';
		                header("Refresh: 2;url=panel.php");

		            }else{

		                echo '<script>swal({
		                      title: "Hata",
		                      text: "Üye Bilgileri Güncellenemedi",
		                      type: "error",
		                      timer: 3000,
		                      showConfirmButton: false
		                    });</script>';
		            }
		        }
		        ?>
		        <form action="" method="post">
		            <input type="hidden" class="form-control" name="id" value="<?=post('id')?>">
		            <div class="form-group col-lg-3">
		                <label style="color:white;">Kullanıcı Adı</label>
		                <input type="text" class="form-control" name="kadi" autofocus="" value="<?=post('kadi')?>">
		            </div>

		            <div class="form-group col-lg-3">
		                <label style="color:white;">Şifre</label>
		                <input type="text" class="form-control" name="sifre" autofocus="" value="<?=post('sifre')?>">
		            </div>

		            <div class="form-group col-lg-2">
		                <label style="color:white;">Rütbe</label>
		                <select class="form-control" name="rutbe">
		                	<option value="<?=post('rutbe')?>" hidden selected>Rütbe</option>
		                	<option value="0">Üye</option>
		                	<option value="1">Yönetici</option>
		                </select>
		            </div>

		            <div class="form-group col-lg-2">
		                <label style="color:white;">Onay</label>
		                <select class="form-control" name="onay">
		                	<option value="<?=post('onay')?>" hidden selected>Onay</option>
		                	<option value="0">Onaysız</option>
		                	<option value="1">Onaylı</option>
		                </select>	                
		            </div>

		            <div class="form-group col-lg-2">
		            <label class="hidden-xs">&nbsp;</label>
		                <input class="form-control btn btn-success" type="submit" name="guncelle" value="Güncelle">
		            </div> 	                                                                              
		        </form>
			</div>
        </div><!-- /#page-content-wrapper -->
    </div><!-- /#wrapper -->			
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
<?php }else { header('Location:index.php');}?>