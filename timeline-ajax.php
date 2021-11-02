<?php
ob_start();
session_start();
require_once "class/BasicDB.php";
require_once "baglan.php";
require_once "fonksiyon.php";

if (post('durumpaylas')) {

    require_once "class/class.upload.php";
    $handle = new upload($_FILES['resim_url']);
    if ($handle->uploaded) {
        $handle->allowed   = array('image/*'); //Bu satır tüm resim uzantılarını kabul eder
        $handle->file_new_name_body   = 'uye_resim';
        $handle->image_convert       = 'webp';
        $handle->image_x              = 1000; //resmin standart genişliği
        $handle->image_ratio_crop     = true; //resimi orantılı hale getirir
        $handle->process('resimler');
        if ($handle->processed) {
         $handle->clean();
        }
    }
    date_default_timezone_set('Europe/Istanbul');
    $ekle = $db->insert('resimler')
    ->set(array(
     'resim_url' => $handle->file_dst_name == '' ? NULL : $handle->file_dst_name,
     'uyeid'     => $_SESSION["uyeid"],
     'tarih'     => date('Y-m-d H:i:s'),
     'aciklama'  => post('aciklama') == '' ? NULL : post('aciklama'),
    ));

    if ($ekle){

        echo "ok";

    }else {

        echo 'hata';
    }
}
if (post('durumsil')) {
    $resimbul = $db->from('resimler')
    ->where('resim_id', post('resimid'))
    ->where('uyeid',post('uyeid'))
    ->first();

    if ($resimbul) {
        @unlink("resimler/".$resimbul["resim_url"]);

        $sil = $db->delete('resimler')
        ->where('resim_id', post('resimid'))
        ->where('uyeid',post('uyeid'))
        ->done();

        if ($sil) {
            echo 'ok';
        }else{
            echo 'hata';
        }
    }else{
        $sil = $db->delete('resimler')
        ->where('uyeid',post('uyeid'))
        ->done();

        if ($sil) {
            echo 'ok';
        }else{
            echo 'hata';
        }
    }
    
}
