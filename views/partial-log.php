<?php

use dcms\pin\includes\Database;

$db = new Database();
$rows = $db->select_log_table(100);

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

    section.msg-top button.btn-export{
        float:right;
    }

    section.msg-top:after{
        content:'';
        clear:both;
        display:block;
    }
</style>

<section class="msg-top">
    <span><?= _e('Recent mailings', DCMS_PIN_TEXT_DOMAIN) ?></span>
    <button class="btn-export button button-secondary"><?php _e('Export all', DCMS_PIN_TEXT_DOMAIN) ?></button>
</section>

<?php
    $fields = [ 'identify', 'pin', 'email', 'date' ];
?>

<table class="dcms-table">
    <tr>
        <?php
        foreach($fields as $field) {
            echo "<th>" . $field . "</th>";
        }
        ?>
    </tr>
    <?php  error_log(print_r($rows,true)); ?>
<?php foreach ($rows as $row):  ?>
    <tr>
        <td><?= $row->identify ?></td>
        <td><?= $row->pin ?></td>
        <td><?= $row->email ?></td>
        <td><?= $row->date ?></td>
    </tr>
<?php endforeach; ?>
</table>



