<header class="header-log-pin">
	<section class="date-range">
		<form method="post" id="frm-search" class="frm-search" action="" >
			Desde: <input type="date" id="date_start" name="date_start"  value="<?= $val_start ?>" />
			Hasta: <input type="date" id="date_end" name="date_end" value="<?= $val_end ?>" />
			<button id="btn-search" type="submit" class="btn-search button button-primary">Filtrar</button>
		</form>
	</section>

	<section class="buttons-export">
		<form method="post" id="frm-export" class="frm-export" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
			<input type="hidden" name="date_start" value="<?= $val_start ?>">
			<input type="hidden" name="date_end" value="<?= $val_end ?>">
			<input type="hidden" name="action" value="process_export_pin_sent">
			<button type="submit" class="btn-export button button-primary"><?php _e('Exportar', 'dcms-send-pin') ?></button>
		</form>
	</section>

</header>

<?php
$fields = [ 'Identificativo', 'PIN', 'correo', 'Número', 'Referencia', 'NIF', 'fecha', '' ];
?>

<table class="dcms-table">
	<tr>
		<?php
		foreach($fields as $field) {
			echo "<th>" . $field . "</th>";
		}
		?>
	</tr>

</table>

