
<?php
$toolbar01 = new ToollBar("right");
$toolbar02 = new ToollBar("right");
$toolbar03 = new ToollBar("right");
$toolbar04 = new ToollBar("right");
$toolbar05 = new ToollBar("right");
$toolbar06 = new ToollBar("right");


$IdExamenLaboratorio = $datos["IdExamenLaboratorio"];
$NombreExamenLaboratorio = $datos["NombreExamenLaboratorio"];
?>

<fieldset style="margin:1px;width:95%;height:auto;padding: 0px; font-size:14px;">
    <legend>&nbsp; Detalle del Examen Laboratorio &nbsp;</legend>

    <table width="100%" border="1">


        <tr>
            <td width="200" align="left">Nombre Examen </td>
            <td width="100"><input type="text" name="txtNombreExamen" id="txtNombreExamen" value="<?php echo $NombreExamenLaboratorio ?>" class="texto_combo" size="50" readonly tabindex="1"/></td>

        </tr>
        <tr>
            <td width="200" align="left">Tipo Muestra </td>
            <td width="100"><input type="text" name="cboTipoExamen" id="cboTipoExamen" value="<?php echo $area ?>" class="texto_combo" size="40" readonly tabindex="1"/></td>

            <td width="200" align="right">
                <div id="idbBuscarCoordinadores" style="">;

                    <?php
                    $toolbar01->SetBoton("NuevoTipoMuestra", "Nuevo", "btn", "onclick,onkeypress", "NuevoTipoMuestra()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/agt_action_success.png", "", "", 1);
                    $toolbar01->Mostrar();
                    ?>

                </div>


            </td>

        </tr>

        <tr>

            <td height="30" width="200">Recipiente :</td>
            <td width="100">


                <input id="txtNombres" name="txtNombres" value="<?php echo $NombreCoordinador ?>" size="40" readonly/>

            </td>

           
<!--           <td align="center" width= "50%" height="30" >-->
          <td align="center"  >
                <div style="" id="modificardiv">

                    <?php
                    $toolbar04->SetBoton("SeleccionarReciente", "Seleccionar", "btn", "onclick,onkeypress", " seleccionarRecipiente()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/agt_action_success.png", "", "", 1);
                    $toolbar04->Mostrar();
                    ?>

                </div>

            </td>
        </tr>
  

    </table>

</fieldset>
<!--
<table width="100%" border="0">




    <tr>
        <td colspan="4" align="center">
            <div id="divResulEncargado" ></div>
            <div id="divMsmResultadoEncargado" style="width: 400px;"></div>
        </td>
    </tr>



</center>
<br/>-->







