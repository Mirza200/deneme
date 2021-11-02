<?php
//error_reporting(0);
ob_start();
session_start();
require_once "class/BasicDB.php";
require_once "baglan.php";
#####RESİMLERİ GÖSTER#####
$listele = $db->from('resimler')
->join('uye', '%s.id = %s.uyeid')
->groupby('tarih')
->orderby('tarih','desc')
->all();
#####RESİMLERİ GÖSTER#####
?>
<?php if (@$_SESSION["oturum"]) { ?>
	<!DOCTYPE html>
	<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Durum Paylaş</title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//fonts.googleapis.com/css?family=Hammersmith+One|Kalam" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script type="text/javascript" language="javascript" src="sweetalert/sweetalert.min.js"></script>
		<link rel="stylesheet" href="sweetalert/sweetalert.css">		
		<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/hamburger.js"></script>
		<link rel="stylesheet" type="text/css" href="css/paylasimlar.css">
		<link rel="stylesheet" type="text/css" href="css/input.css">
		<link rel="stylesheet" type="text/css" href="css/hamburger.css">
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
					<div class="row" id="paylas">
						<div class="gizle">
							<form enctype="multipart/form-data" method="post" id="durumpaylas" action="">
								<div class="form-group col-lg-2 text-center">
									<label class="file">
										<input type="file" name="resim_url">
										<span class="file">
											<i class="fa fa-picture-o" aria-hidden="true"></i> Fotoğraf Seç!
										</span>
									</label>
								</div>

								<div class="form-group col-lg-8">
									<textarea type="text" name="aciklama" rows="1" class="form-control" placeholder="Ne düşünüyorsun, <?=$_SESSION['uyeAdi'] ?>?"></textarea>
								</div>
								<div class="form-group col-lg-2">
									<input type="hidden" name="durumpaylas" value="1">
									<input type="submit" name="ekle" class="btn btn-info btn-block paylas" value="Paylaş">
								</div>
							</form>
						</div>
						<div class="alert alert-success uyari" style="margin:0px;">
							<strong>Paylaşılıyor!</strong> Yüklenmesi biraz zaman alabilir.
						</div>
					</div>

					<div class="col-lg-12">
						<div class="row">
							<div class="timeline timeline-line-dotted">
								<?php if ($listele){foreach ( $listele as $res ){?>
									<span class="timeline-label">
										<span class="label label-primary"><?=date('d.m.Y',strtotime($res["tarih"]))?></span>
									</span>
									<?php
									$icerik = $db->from('resimler')
									->join('uye', '%s.id = %s.uyeid')
									->orderby('tarih', 'desc')
									->where('tarih', $res["tarih"])
									->all();
							//print_r($icerik);
									foreach ($icerik as $i) { ?>
										<?php if (($i["id"] == $_SESSION["uyeid"])) { ?>
											<div class="timeline-item">
												<div class="timeline-point timeline-point-<?=$i['resim_url'] == '' ? 'default' : 'danger'?>">
													<?= $i['resim_url'] == '' ? '<i class="fa fa-pencil"></i>' : '<i class="fa fa-file-image-o"></i>'?>
												</div>

												<div class="timeline-event">
													<div class="timeline-heading">
														<span style="background-color:<?=$i["krengi"] ?>;padding:5px;padding-bottom:7px;border-radius:4px">
															<a href="<?=$i["uyeAdi"]?>" id="kadi" style="color:white;text-decoration:none">
																<span class="badge" style="background-color:white;border-radius:10px">
																	<?=$i["rutbe"] == 1 ? '<span style="color:'.$i["krengi"].'" class="glyphicon glyphicon-star"></span>' : '<span style="color:'.$i["krengi"].'" class="glyphicon glyphicon-plus"></span>' ?>
																</span>
																<?=$i["uyeAdi"]?>
																<?=$i["cinsiyet"] == 1 ? '<i class="fa fa-mars"></i>' : '<i class="fa fa-venus"></i>' ?>
															</span>

														</a>
													</div>
													<div class="timeline-body">
														<?= $i['aciklama'] == null ? '' : '<p>'.$i['aciklama'].'</p>'?>
														<?= $i['resim_url'] == null ? '' : '<img class="img img-responsive img-rounded" src="resimler/'.$i["resim_url"].'">'?>
													</div>

													<div class="timeline-footer">
														<ul>
															<li><i class="fa fa-clock-o"></i> <?=date('H:i',strtotime($i["tarih"]))?></li>

															<?= $i['resim_url'] == '' ? '' : '<li><a href="resimler/'.$i["resim_url"].'" download>  <span class="fa fa-download"></span> İndir</a></li>'?>

															<?php if ($i["uyeid"] == $_SESSION["uyeid"]) { ?>
																<li>
																	<form action="" method="post" id="durumsil">
																		<input type="hidden" name="resimid" value="<?=$i["resim_id"]?>">
																		<input type="hidden" name="uyeid" value="<?=$_SESSION["uyeid"]?>">
																		<input type="hidden" name="durumsil" value="1">
																		<button type="submit"><span class="fa fa-window-close"></span> Sil</button>
																	</form>
																</li>
															<?php } ?>

															<?php if ($i["uyeid"] == $_SESSION["uyeid"]) { ?>

																	<li>
																		<form action="" method="post" id="durumsil">
																			<input type="hidden" name="resimid" value="<?=$i["resim_id"]?>">
																			<input type="hidden" name="uyeid" value="<?=$_SESSION["uyeid"]?>">
																			<input type="hidden" name="durumsil" value="1">
																		</form>
																	</li>

															<?php } ?>                		
														</ul>
													</div>
												</div>
											</div>
										<?php } ?>
									<?php }	} }	?>
									<span class="timeline-label">
										<a href="#" class="btn btn-default" title="More...">
											<i class="fa fa-fw fa-history"></i>
										</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div><!-- /#page-content-wrapper -->
			</div><!-- /#wrapper -->
			<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
			<script type="text/javascript">
			$(function(){
				$(".uyari").hide();
				$(".paylas").click(function(){
					$(".gizle").slideUp(2000);
					$(".uyari").show();
				});
				$(".uyari").click(function(){
					$(".uyari").hide();
					$(".gizle").show();
				});

			});

			$(document).ready(function(){
				setTimeout(function(){
					$("div#kaybol").fadeOut("slow", function () {
						$("div#kaybol").remove();
					});
				}, 15000);
			});		
</script>
<script type="text/javascript">
 $("#durumpaylas").submit(function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    type: "POST",
    url: 'timeline-ajax.php',
    data:formData,
    cache:false,
    contentType: false,
    processData: false,
    success: function(data){
      if (data == "hata"){
        swal({
          title: "Hata",
          text: "Durum paylaşılamadı!.",
          type: "error",
          timer: 3000,
          showConfirmButton: false
        });
      }
      if (data == "ok"){
        swal({
          title: "Başarılı",
          text: "Durum Paylaşıldı.",
          type: "success",
          timer: 2000,
          showConfirmButton: false
        });
        $('textares[name=aciklama').val('');
        $('file[name=resim_url').val('');
        setInterval(()=>window.location.reload(false),2000);
      }
    }
  });
});
</script>
<script type="text/javascript">
 $("#durumsil").submit(function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    type: "POST",
    url: 'timeline-ajax.php',
    data:formData,
    cache:false,
    contentType: false,
    processData: false,
    success: function(data){
      if (data == "hata"){
        swal({
          title: "Hata",
          text: "Paylaşım silinemedi. Paylaşımın size ait olduğundan emin olunuz.",
          type: "error",
          timer: 3000,
          showConfirmButton: false
        });
      }
      if (data == "ok"){
        swal({
          title: "Başarılı",
          text: "Paylaşım tamamen silindi.",
          type: "success",
          timer: 2000,
          showConfirmButton: false
        });
        setInterval(()=>window.location.reload(false),2000);
      }
    }
  });
});
</script>
		</body>
		</html>
<?php }else { //oturum yoksa yönlendir
	header('Location:index.php');
}
?>