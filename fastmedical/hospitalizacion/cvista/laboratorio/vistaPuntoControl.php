<div id="divContenidoPuntoControl" style="width:1000px; height:600px;  margin:1px auto; border: #006600 solid">
    <div class="titleform">
        <h1>Mantenimiento de Puntos de Control</h1>
    </div>
    <div id="divPuntoControl" class="toolbar" style="width:400px;float: left; height: 550px; ">
        <div id="divBuscador" class="toolbar" >
            Buscar: 
            <input id="txtPuntoControlBusqueda" name="txtPuntoControlBusqueda"  style="width:300px" onkeyup="FiltrarPuntoControl()">
        </div>
        <div  id="divResultadoPuntoControl" class="toolbar" style="height: 480px;width: 390px;">
            Resultado
        </div>
    </div>
    <div id="divDetallePuntoControl" class="toolbar" style="width: 560px; float: left; height: 250px;">



        <?php
        $toolbar1 = new ToollBar();
        $toolbar2 = new ToollBar();
        $toolbar3 = new ToollBar();
        $toolbar4 = new ToollBar();
        ?>
        <fieldset style="margin:1px;width:500px;height: 196px;padding: 0px; font-size:1.2em;">
            <legend>&nbsp; Datos del Punto de Control &nbsp;</legend>
            <div style="padding: 10px;">
                <div style="height:30px; width:200px" id="fila1">
                    <div style="float:left; width:50px;" id="cell11">ID:</div>
                    <div style="float:right; width:100px;" id="cell12">
                        <input readonly type="text"  style="width: 30px;" id="txtIdPuntoControl" name="txtIdPuntoControl" value="">
                    </div>
                </div><div style="clear: both;height: 10px"></div>

                <div style="height:30px; width:200px" id="fila1">
                    <div style="float:left; width:50px;" id="cell11">Nombre:</div>
                    <div style="float:right; width:100px;" id="cell12">
                        <input type="text" style="width: 250px;" id="txtNombre" name="txtNombre" value="">
                    </div>
                </div><div style="clear: both;height: 10px"></div>

                <div style="height:30px; width:200px" id="fila1">
                    <div style="float:left; width:50px;" id="cell11">Descripción:</div>
                    <div style="float:right; width:100px;" id="cell12">
                        <textarea  style="width: 250px;" id="textAreaDescripcion" name="textAreaDescripcion" ></textarea>
                    </div>
                </div><div style="clear: both;height: 10px"></div>

                <div style="height:30px; width:200px" id="fila1">
                    <div style="float:left; width:50px;" id="cell11">Estado:</div>
                    <div style="float:right; width:100px;" id="cell12">
                        <input id="bEstado"  type="checkbox" name="bEstado"  />
                    </div>
                    <br>
                    <br>
                    <div style="height:30px; width:450px" id="ContenedorTransacciones">

                        <div style="width: 100px; float: left; height: 200px;"id="Transacciones1">
                            <?php
                            $toolbar1->SetBoton("agregarPuntoControl", "Nuevo", "btn", "onclick,onkeypress", "agregarPuntoControl()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/kopeteavailable.png", "", "", 0);
                            $toolbar1->Mostrar();
                            ?> 
                        </div>
                        <div style="width: 100px; float: left; height: 100px;" id="Transacciones2">
                            <?php
                            $toolbar2->SetBoton("habilitarFormularioPuntoControl", "Editar", "btn", "onclick,onkeypress", "habilitarFormularioPuntoControl()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/edit2.png", "", "", 0);
                            $toolbar2->Mostrar();
                            ?>  
                        </div>
                        <div style="width: 100px; float: left; height: 100px;" id="Transacciones3">
                            <?php
                            $toolbar3->SetBoton("guardarPuntoControl", "Guardar", "btn", "onclick,onkeypress", "guardarPuntoControl()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/filesave.png", "", "", 1);
                            $toolbar3->Mostrar();
                            ?>  
                        </div>    
                        <div style="width: 100px; float: left; height: 100px;" id="Transacciones4">
                            <?php
                            $toolbar4->SetBoton("cancelarPuntoControl", "Cancelar", "btn", "onclick,onkeypress", "cancelarPuntoControl()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/button_cancel.png", "", "", 1);
                            $toolbar4->Mostrar();
                            ?>  
                        </div>    

                    </div>

                </div>
            </div>

        </fieldset>

    </div>
    <div id="divAsignacionUsuariosXPuntosControl" style="display: none">
        <legend>&nbsp;&nbsp; Usuarios Habilitados &nbsp;</legend>
        <div  id="divTablaUsuariosHabilitados"  style="width: 500px; float: left; height: 150px;">
        </div>
        <div style="height:30px; width:450px" id="ContenedorBotones">

            <div style="width: 10px; float: left; height: 10px;" id="Transacciones9">
                <?php
                $toolbar1->SetBoton("BusquedaEmpleado", "Buscar Empleado", "btn", "onclick,onkeypress", "podpadBusquedaEmpleadoPuntoControl()", $_SESSION['path_principal'] . "../fastmedical_front/imagen/icono/kopeteavailable.png", "", "", 1);
                $toolbar1->Mostrar();
                ?>  
            </div>    
        </div>
    </div>
</div>