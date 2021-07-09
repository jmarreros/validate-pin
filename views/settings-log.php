<?php

?>
<style>
    table.dcms-table{
        width:100%;
        background-color:white;
        border-spacing: 0;
    }

    table.dcms-table th,
    table.dcms-table td{
        text-align:left;
        padding:6px;
        border-bottom:1px solid #ccc;
    }

    table.dcms-table tr th:first-child,
    table.dcms-table tr td:first-child{
        width:40px;
        background-color:#aaa;
    }

    table.dcms-table th,
    table.dcms-table tr th:first-child{
        background-color:#23282d;
        color:white;
    }

    table.dcms-table tr td:nth-child(1),
    table.dcms-table tr td:nth-child(2){
        font-weight:bold;
    }

    section.msg-top{
        margin-top:20px;
        padding:10px;
    }

    section.msg-top .frm-export{
        float:right;
    }

    section.msg-top:after{
        content:'';
        clear:both;
        display:block;
    }

    .header-log-pin{
        display:flex;
        padding:20px;
    }
    .header-log-pin > section{
        width:50%;
    }

    .buttons-export{
        text-align:right;
    }
</style>


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
<?php foreach ($rows as $row):  ?>
    <tr>
        <td><?= $row->identify ?></td>
        <td><?= $row->pin ?></td>
        <td><?= $row->email ?></td>
        <td><?= $row->number ?></td>
        <td><?= $row->reference ?></td>
        <td><?= $row->nif ?></td>
        <td><?= $row->date ?></td>
        <td><a href="#" class="resend">Reenviar</a></td>
    </tr>
<?php endforeach; ?>
</table>

