<tr>
	<td>
		<a href="javascript:void(0)" class="btn btn-danger btn-sm sgp-remove-contact"><i class="fa fa-minus"></i></a>
		<?php
		if( $id_recapito_telefonico != '' ) {
		?>
			<button type="button" class="btn btn-warning btn-sm sgp-edit-contatto" data-toggle="modal" data-target="#modal" id="sgp-edit-contatto-<?=$id_recapito_telefonico?>"><i class="fa fa-pencil"></i></button>
		<?php
		}
		?>
		<input name="id_recapito_telefonico[]" value="<?=$id_recapito_telefonico?>" hidden="true">
	</td>
	<td><input name="id_tipologia_numero[]" value="<?=$id_tipologia_numero?>" hidden="true"><?=ucwords($tipologia_numero)?><input name="new_tipologia_numero[]" hidden="true" value="<?=$new_tipologia_numero?>"></td>
	<td><input name="numero[]" value="<?=$numero?>" hidden="true"><?=$numero?></td>
</tr>