<?php
if ($_SESSION["oturum"]) {

	if (post('guncelle')) {

		$resim = $db->from('uye')
		->where('id', $_SESSION["uyeid"])
		->first();
		//Klasörlerden resimleri silelim
		@unlink("profil/".$resim['profil_resmi']);
		@unlink("kapak/".$resim['profil_resmi']);
		//Veritabanından da silelim
		$guncelle = $db->update('uye')
        ->where('id', $_SESSION["uyeid"])
        ->set(array(
             'profil_resmi' => NULL
        ));
		   
	    //Yeni eklenen Resmi İki farklı boyutta alalım
	    $handle = new upload($_FILES['resim_url']);
	    if ($handle->uploaded) {
	        $handle->allowed   = array('image/*'); //Bu satır tüm resim uzantılarını kabul eder
	        $handle->file_new_name_body   = 'kapak';
	        $handle->image_resize         = true;
	        $handle->image_x              = 150; //resmin standart genişliği
	        $handle->image_y              = 150; //resmin standart yüksekliği
	        $handle->image_ratio_crop     = true; //resimi orantılı hale getirir
	        $handle->process('profil');
	        //İkinci resim boyutu
	        $handle->allowed   = array('image/*'); //Bu satır tüm resim uzantılarını kabul eder
	        $handle->file_new_name_body   = 'kapak';
	        $handle->image_resize         = true;
	        $handle->image_x              = 710; //resmin standart genişliği
	        $handle->image_y              = 310; //resmin standart yüksekliği
	        $handle->image_ratio_crop     = true; //resimi orantılı hale getirir
	        $handle->process('kapak');

	        if ($handle->processed) {
	        //echo 'Resim Başarıyla Yüklendi.';
	        //print '<img src="' . $image->file_dst_path . $image->file_dst_name . '" alt="" />';
	        $handle->clean();
	            } else {
	            echo 'Hata Var : ' . $handle->error;
	        }
	    }

		$query = $db->update('uye')
	    ->where('id', $_SESSION["uyeid"])
	    ->set(array(
	         'uyeAdi' => post('kadi'),
	         'sifre' => post('sifre'),
	         'krengi' => post('krengi'),
	         'yrengi' => post('yrengi'),
	         'cinsiyet' => post('cinsiyet'),
	         'hakkinda' => post('hakkinda'),
	         'profil_resmi' => $handle->file_dst_name,
	         'sure' => time()
	    ));
		if ( $query ){
		  header('Location:'.@$uye['seoadi']);
		}

	}

	$resim = $db->from('uye')
	->where('id', $_SESSION["uyeid"])
	->first();

	$uye = $db->from('uye')
	->where('id', $_SESSION["uyeid"])
	->first();
	?>
	    <form action="" method="post" enctype="multipart/form-data">
	    	<div class="row">
	    		<div class="form-group col-lg-3 text-center">
	    			<img src="<?=$resim['profil_resmi'] == NULL ? 'http://via.placeholder.com/200x200' : 'profil/'.$resim['profil_resmi'] ?>" class="img img-thumbnail img-responsive">

					<label class="file" style="padding-top: 5px;">
					    <input type="file" name="resim_url">
					    <span class="file">
					        <i class="fa fa-picture-o" aria-hidden="true"></i> Fotoğraf Seç!
					    </span>
					</label>

	    		</div>
	    		<div class="form-group col-lg-9">
	        		<label>Hakkınızda</label>
			        <textarea class="form-control" name="hakkinda" rows="6"><?=$uye['hakkinda']?></textarea>
			    </div>
		    </div>
  		
			<div class="row">
	        	<div class="form-group col-lg-2">
		        	<label>Kullanıcı Adı</label>
			        <input type="text" class="form-control" name="kadi" value="<?=$uye['uyeAdi']?>">
		        </div>

		        <div class="form-group col-lg-2">
			        <label>Şifre</label>
			        <input type="text" class="form-control" name="sifre" value="<?=$uye['sifre']?>">
		        </div>

		        <div class="form-group col-lg-2">
					<label>Cinsiyet</label>
					<select name="cinsiyet" class="form-control">
						<option hidden selected value="<?=$uye['cinsiyet']?>"><?=$uye['cinsiyet']==1 ? 'Erkek' : 'Bayan'?></option>
						<option style="color:black;" value="1">Erkek</option>
						<option style="color:black;" value="2">Bayan</option>
					</select>
				</div>

		        <div class="form-group col-lg-3">
			        <label>Kullanıcı Rengi</label>
			        <input class="form-control" type="color" list="color" name="krengi" value="<?=$uye['krengi']?>">  
			        <datalist id="color">  
			            <option value="#1abc9c" label="Haki"></option>  
			            <option value="#16a085" label="K.Haki"></option>  
			            <option value="#2ecc71" label="Yeşil"></option>  
			            <option value="#27ae60" label="K.Yeşil"></option>  
			            <option value="#3498db" label="Mavi"></option>  
			            <option value="#2980b9" label="K.Mavi"></option>  
			            <option value="#9b59b6" label="Mor"></option>  
			            <option value="#8e44ad" label="K.Mor"></option>  
			            <option value="#34495e" label="Gri"></option>  
			            <option value="#2c3e50" label="K.Gri"></option>
			            <option value="#f1c40f" label="Sarı"></option>  
			            <option value="#f39c12" label="K.Sarı"></option>
			            <option value="#e67e22" label="Turuncu"></option> 
			            <option value="#d35400" label="K.Turuncu"></option>
			            <?php echo $uye['rutbe'] == 1 ? '
			            <option value="#e74c3c" label="Kırmızı"></option> 
			            <option value="#c0392b" label="K.Kırmızı"></option>'
			            : ''; ?>
			        </datalist>
		        </div>

		        <div class="form-group col-lg-3">
			        <label>Yazı Rengi</label>
		        	<input type="color" class="form-control" list="color" name="yrengi" value="<?=$uye['yrengi']?>">
			        <datalist id="color">  
			            <option value="#1abc9c" label="Haki"></option>  
			            <option value="#16a085" label="K.Haki"></option>  
			            <option value="#2ecc71" label="Yeşil"></option>  
			            <option value="#27ae60" label="K.Yeşil"></option>  
			            <option value="#3498db" label="Mavi"></option>  
			            <option value="#2980b9" label="K.Mavi"></option>  
			            <option value="#9b59b6" label="Mor"></option>  
			            <option value="#8e44ad" label="K.Mor"></option>  
			            <option value="#34495e" label="Gri"></option>  
			            <option value="#2c3e50" label="K.Gri"></option>
			            <option value="#f1c40f" label="Sarı"></option>  
			            <option value="#f39c12" label="K.Sarı"></option>
			            <option value="#e67e22" label="Turuncu"></option> 
			            <option value="#d35400" label="K.Turuncu"></option>
			            <?php echo $uye['rutbe'] == 1 ? '
			            <option value="#e74c3c" label="Kırmızı"></option> 
			            <option value="#c0392b" label="K.Kırmızı"></option>'
			            : ''; ?>
			        </datalist>					        	
		        </div>
		    </div>

		    <div class="row">
			    <div class="form-group col-lg-2">
			    	<label class="hidden-xs"></label>
			        <input type="submit" name="guncelle" class="form-control btn btn-success" value="Güncelle">
		        </div>
	        </div>        
	    </form>	
<?php } ?>