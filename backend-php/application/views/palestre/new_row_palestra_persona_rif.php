<tr>
	<td>
		<a href="javascript:void(0)" class="btn btn-danger btn-sm sgp-remove-riferimento"><i class="fa fa-minus"></i></a>
		<?php
		if( $id_riferimento != '' ) {
		?>
			<button type="button" class="btn btn-warning btn-sm sgp-edit-riferimento" data-toggle="modal" data-target="#modal" id="sgp-edit-persona-riferimento-<?=$id_riferimento?>"><i class="fa fa-pencil"></i></button>
		<?php
		}
		?>
		<input name="id_riferimento[]" value="<?=$id_riferimento?>" hidden="true">
	</td>
	<td class=""><input name="nome_riferimento[]" value="<?=$nome_riferimento?>" hidden="true"><?=ucwords($nome_riferimento)?></td>
	<td class=""><input name="cognome_riferimento[]" value="<?=$cognome_riferimento?>" hidden="true"><?=ucwords($cognome_riferimento)?></td>
	<td class=""><input name="id_ruolo_riferimento[]" value="<?=$id_ruolo_riferimento?>" hidden="true"><input name="new_ruolo_riferimento[]" value="<?=$new_ruolo_riferimento?>" hidden="true"><?=ucwords($ruolo_riferimento)?></td>
	<td class="">
		<input name="telefono_riferimento[]" value="<?=$telefono_riferimento?>" hidden="true">
<?php
if( $telefono_riferimento != '' ) {
?>
		<?=$telefono_riferimento?>
<?php
} else {
?>
		n.d.
<?php
}
?>
	</td>
	<td class="">
		<input name="cellulare_riferimento[]" value="<?=$cellulare_riferimento?>" hidden="true">
<?php
if( $cellulare_riferimento != '' ) {
?>
		<?=$cellulare_riferimento?>
<?php
} else {
?>

		n.d.

<?php
}
?>			
	</td>
	<td class="col-lg-4"><input name="email_riferimento[]" value="<?=$email_riferimento?>" hidden="true"><?=$email_riferimento?></td>
</tr>	