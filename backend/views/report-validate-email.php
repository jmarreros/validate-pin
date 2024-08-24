<header class="header-log-pin">
    <section class="date-range">
        <form method="post" id="frm-search-validate" class="frm-search" action="">
            Desde: <input type="date" id="date_start_validate" name="date_start_validate" value="<?= $val_start ?>"/>
            Hasta: <input type="date" id="date_end_validate" name="date_end_validate" value="<?= $val_end ?>"/>
            <button id="btn-search" type="submit" class="btn-search button button-primary">Filtrar</button>
        </form>
    </section>

</header>

<?php
$fields = [ 'Identificativo', 'Correo', 'Validado', 'Unique', 'Fecha' ];
?>

<table class="dcms-table">
    <tr>
		<?php
		foreach ( $fields as $field ) {
			echo "<th>" . $field . "</th>";
		}
		?>
    </tr>

	<?php foreach ( $rows as $row ): ?>
        <tr>
            <td><?= $row->user_login ?></td>
            <td><?= $row->email ?></td>
            <td><?= $row->validated ?></td>
            <td><?= $row->unique_id ?></td>
            <td><?= $row->date ?></td>
        </tr>
	<?php endforeach; ?>
</table>

