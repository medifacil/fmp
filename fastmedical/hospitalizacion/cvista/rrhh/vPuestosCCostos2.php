<div style="width:900px; margin:1px auto; border: #006600 solid">
    <div class="titleform">
        <h1>Configuraci&oacute;n de Puestos Laborales por Centro de Costos</h1>
    </div>
    <div  id ="divPuestosCCostos" style="width:99%;margin-left:0.5%;margin-right:0.2%;overflow: hidden;">
        <div  id ="divIzqPuestosCCostos" style=" float:left;width:38%;">
            <div  id ="divIzqInfPuestosCCostos" style=" width:100%;" >
                <div style="height: 70px; width: 90%" id="toolbar">
                    <input type="hidden"   id="hCcosto" name="hCcosto" value="1"/>
                    <div style="width: 100%; height: 35%;">
                        <div style="width: 20%; float: left;" id="divEtiquetaPuesto">
                            Puesto:
                        </div>
                        <div style="width: 80%; float: left;" id="DivTextPuesto">
                            <input type="text" size="30" value="" onkeypress="verPuestos('x',event,'detallePuestoCentro');"  id="txtPuesto" name="txtPuesto"/>
                        </div>
                    </div>
                    <!--                    <div style="width: 100%; height: 35%;">
                                            <div style="width: 20%; float: left;" id="divEtiquetaNroOrden">
                                                Categoria:
                                            </div>
                                            <div style="width: 80%; float: left;" id="DivSelectTipoDoc">
                                                <select name="select" id="comboCategoriaPuestos" style="width:150px; font-size:9px" >
                    <?php
                    echo $comboHTML;
                    ?>
                                                </select>
                                            </div>
                                        </div>-->
                    <div style="width: 100%; height: 30%;">
                        <div style="width: 20%; float: left;" id="divEtiquetaNroOrden">
                            Estado:
                        </div>
                        <div style="width: 20%; float: left;" id="DivSelectTipoDoc">
                            <select name="select" id="comboEstados" style="width:60px; font-size:9px" >
                                <option value="1">Todos</option>
                                <option value="2">Activos</option>
                                <option value="3">Inactivos</option>
                            </select>

                        </div>
                        <div style=" width: 30%; float: left; margin-left: 20px;" id="DivBuscar" >
                            <a href="javascript:verPuestos('x','','detallePuestoCentro');"><img border="0" title="Codigo de Persona" alt="" src="../../../../fastmedical_front/imagen/btn/nbtn_buscar.gif"/></a>
                        </div>
                    </div>
                </div>
                <div style="width: 100%; height:30px;" align="center">
                    <input type="text" id="txtBusquedaArbolx" name="txtBusquedaArbolx" onkeypress="if(event.keyCode==13)busquedaArbol2();" /> <a onClick="busquedaArbol2()" style="cursor: pointer">Buscar</a>
                </div>
                <div  id ="divOpcCCostos" style=" float:left;width:99%; height:470px;"></div>
            </div>
        </div>



        <div  id ="divDerPuestosCCostos" style=" float:right;width:61%;">
            <div id="toolbar" style=" margin-top:15px;">
                <div id="botones" style="height:30px;">
                    <?php
                    if (isset($_SESSION["permiso_formulario_servicio"][204]["EDITAR_PUESTO_X_CCOSTO"]) && ($_SESSION["permiso_formulario_servicio"][204]["EDITAR_PUESTO_X_CCOSTO"] == 1)) {
                        //<a href="javascript:editarDetallePuesto();"><img id="imagenEditar" style=" display: none;" src="../../../../fastmedical_front/imagen/btn/b_editar_on.gif"/></a>
                        echo "<a href=\"javascript:editarDetallePuesto();\"><img id=\"imagenEditar\" style=\" display: none;\" src=\"../../../../fastmedical_front/imagen/btn/b_editar_on.gif\"/></a>";
                    } else {
                        echo "<img id=\"imagenEditar\" style=\" display: none;\" src=\"../../../../fastmedical_front/imagen/btn/b_editar_on.gif\"/>";
                    }

                    if (isset($_SESSION["permiso_formulario_servicio"][204]["AGREGAR_PUESTO_X_CCOSTO"]) && ($_SESSION["permiso_formulario_servicio"][204]["AGREGAR_PUESTO_X_CCOSTO"] == 1)) {
                        //<a href="javascript:agregarDetallePuesto();"><img id="imagenAgregar" src="../../../../fastmedical_front/imagen/btn/b_agregar_on.gif"/></a>
                        echo "<a href=\"javascript:agregarDetallePuesto();\"><img id=\"imagenAgregar\" src=\"../../../../fastmedical_front/imagen/btn/b_agregar_on.gif\"/></a>";
                    } else {
                        echo "<img id=\"imagenAgregar\" src=\"../../../../fastmedical_front/imagen/btn/b_agregar_on.gif\"/>";
                    }
                    ?>
                </div>
            </div>



            <div id="divResultadoPuestosCCostos" style=" height:250px; width:99%; margin-left:2px; overflow: auto;">
                <?Php
                echo $tablaPuestos;
                ?>
            </div>

            
            <div  id ="iddetallePuestosCCostos" style="width:100%;height:auto;">
                <?php
                require_once("../../cvista/rrhh/vDetallePuesto.php");
                ?>

            </div>


        </div>


    </div>
</div>

<!--
<input type="submit" value="Pulsar" onclick="cargarArbolCentroDeCostos()" />
prueba20-->

<input type="submit" value="Pulsar" onclick="prueba20()" />

<div id="arbolAreas" align="center" style="width: 25%;height: 660px; background: white;" >
</div>