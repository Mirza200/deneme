<?php
include 'class/BasicDB.php';
include 'baglan.php';
if ($_POST) {
	$il = $db->from('ilceler')
	->where('il_id', post('id'))
	->orderby('adi', 'asc')
	->all();
	foreach ( $il as $i ){
	echo '<option value="'.$i['id'].'">'.$i['adi'].'</option>';
	}
}
?>