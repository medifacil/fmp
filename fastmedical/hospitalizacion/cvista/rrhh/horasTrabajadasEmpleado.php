<?php
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=archivo.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <tr>
        <td align="center" colspan="7" bgcolor="#6DDA6D">
            Sucursal : <?php echo $descriSucursal;?>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="7" bgcolor="#6DDA6D">
            Modalidad Contrato : <?php echo $descriContrato;?>
        </td>
    </tr>
    <tr>
        <td colspan="3" bgcolor="#6DDA6D">Corte Inicial : <?php echo $txtFechaIni;?></td>
        <td colspan="4" bgcolor="#6DDA6D">Corte Final : <?php echo $txtFechaFin;?></td>
    </tr>
    <tr>
        <td bgcolor="#91E391">Codigo</td>
        <td bgcolor="#91E391">Nombres</td>
        <td bgcolor="#91E391">&Aacute;rea</td>
        <td bgcolor="#91E391">Tot. Horas Trabajadas</td>
        <td bgcolor="#91E391">Tot. Horas Programadas</td>
        <td bgcolor="#91E391">Tardanza (Minitos)</td>
        <td bgcolor="#91E391">Inasistencia (Horas)</td>
    </tr>
    <?php foreach($arrayDatos as $i=>$val) { ?>
    <tr>
        <td><?php echo $val[0]?></td>
        <td><?php echo $val[1]?></td>
        <td><?php echo $val[2]?></td>
        <td><?php echo $val[3]?></td>
        <td><?php echo $val[4]?></td>
        <td><?php echo $val[5]?></td>
        <td><?php echo $val[6]?></td>
    </tr>
        <?php }?>
</table>