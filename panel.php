<?php
//error_reporting(0);
if(!isset($_SESSION)) { 
  session_start(); 
} 
ob_start();

require_once "class/BasicDB.php";
require_once "baglan.php";
require_once "class/class.upload.php";
if($_POST) {
	$sil = $db->delete('uye')
	->where('id', post('id'))
	->done();
	}
?>
<?php if (@$_SESSION["oturum"] and $_SESSION["rutbe"] == 1) { ?>
<!DOCTYPE html>
<html lang="tr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Yönetim Paneli</title>
		<!-- Bootstrap CSS -->
		<script type="text/javascript" language="javascript" src="js/jquery-3.2.1.min.js"></script>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="//fonts.googleapis.com/css?family=Hammersmith+One|Kalam" rel="stylesheet">
		<script type="text/javascript" language="javascript" src="sweetalert/sweetalert.min.js"></script>
    	<link rel="stylesheet" href="sweetalert/sweetalert.css">
		<script type="text/javascript" src="js/hamburger.js"></script>
		<link rel="stylesheet" type="text/css" href="css/hamburger.css">
		<!-- Datatables CSS -->
	    <link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
	    <style type="text/css">
			@import url("//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
			body {background-color: #2980b9;}
			.sweet-alert button.cancel{
	  		background-color: #3498db;
	  		color: white;
			}         
		</style>
	    <!-- Datatables Js -->
	    
	    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	    <script type="text/javascript">
		    $(document).ready(function() {
		        var t = $('#example').DataTable( {
		            "ordering": false,
		            "language": {
		                url: '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Turkish.json'
		            }
		        } );

		    t.on( 'order.dt search.dt', function () {
		        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();     
		    } );
	    </script>
		<script type="text/javascript">
			$(document).ready(function(){
			    $(".btn-danger").on("click",function(){ //Gönderme butonunun clasını yakaladım silme butonu
			        
			        var id = jQuery(this).prevAll('input[name="id"]').val();
			        
			        var Data = "id="+id;
			        //alert(Data);

			        swal({
			          title: "Emin misiniz?",
			          text: "<strong style='text-transform:capitalize;'><?=$_SESSION["uyeAdi"]?>, devam ederseniz bu üye tamamen silinecek!</strong>",
			          html: true,
			          type: "warning",
			          showCancelButton: true,
			          confirmButtonColor: "#e74c3c",
			          confirmButtonText: "Evet, silinsin!",
			          cancelButtonText: "Hayır, vazgeç!",
			          closeOnConfirm: false,
			          closeOnCancel: false
			        },
			        function(isConfirm){
			          if (isConfirm) {
			            $.ajax({
			                type: "POST",
			                url: "",//silme işlemi başka sayfada olacaksa dosya adı gir
			                data: Data,

			            });

			            swal({
			                title: "Silindi!", 
			                text: "<strong >Başarıyla Silindi.</strong>", 
			                type: "success",
			                html: true,
			                timer: 2000},
			               function(){ 
			                   location.reload();
			               }
			            );

			          } else {

			            swal({
			              title: "İptal",
			              text: "<strong>Silme İşlemi İptal Edildi.</strong>",
			              type: "error",
			              html: true,
			              timer: 3000
			            });            
			          }
			        });        
			    });
			})  
		</script>
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
			<div class="container" style="padding-top:60px;">
	            <div class="table-responsive" style="background-color: #3498db; color:white; border-radius: 5px; padding:10px;">
	                <table id="example" class="table table-bordered table-hover table-striped" style="background-color: white; border-radius: 5px; color:gray;">
	                    <thead>
	                        <tr>
	                        	<th width="2%">#</th>
	                            <th>Kullanıcı Adı</th>
	                            <th>Şifre</th>
	                            <th>Rütbe</th>
	                            <th>Onay</th>
	                            <th width="2%"></th>
	                            <th width="2%"></th>
	                        </tr>
	                    </thead>               
	                    <tbody>
	                    <?php
	                    $query = $db->from('uye')
	                    ->orderby('rutbe', 'desc')
	                       ->all();
	                    if ( $query ){
	                      foreach ( $query as $uye ){
	                        echo '<tr>
	                        	<td></td>
	                            <td>'.$uye["uyeAdi"].'</td>
	                            <td>'.$uye["sifre"].'</td>';
	                        echo $uye["rutbe"] == 1 ? '<td class="text-success">Yönetici</td>' : '<td class="text-danger">Üye</td>';
	                        echo $uye["onay"] == 1 ? '<td class="text-success">Onaylı</td>' : '<td class="text-danger">Onaysız</td>';
	                        echo '<td>
	                                <form action="panel-duzenle.php" method="post">
	                                    <input type="hidden" name="id" value="'.$uye["id"].'">
	                                    <input type="hidden" name="kadi" value="'.$uye["uyeAdi"].'">
	                                    <input type="hidden" name="sifre" value="'.$uye["sifre"].'">
	                                    <input type="hidden" name="rutbe" value="'.$uye["rutbe"].'">
	                                    <input type="hidden" name="onay" value="'.$uye["onay"].'">
	                                    <button class="btn btn-primary btn-xs" type="submit" name="duzenle"><i class="fa fa-pencil-square-o"></i></b>
	                                </form>
	                            </td>
	                            <td>
	                                <form action="" onsubmit="return false;">
	                                <input type="hidden" id="id" name="id" value="'.$uye["id"].'">
	                                <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-window-close"></i></button>
	                                </form>
	                            </td></tr>';
	                      }
	                    }
	                    ?>
	                    </tbody>
	                </table>
	            </div>
			</div>
        </div><!-- /#page-content-wrapper -->
    </div><!-- /#wrapper -->			
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>
<?php }else { header('Location:index.php');}?>