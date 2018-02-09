<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <!-- <link href="XXX.css" rel = "stylesheet" type = "text/css"/>-->
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id = "parteSuperior">
            <div class="nuevaTarea">
                <a id = "nuevaTareaLink" href="NuevaTarea.php">Nueva Tarea</a>
            </div>
            <div class="nuevoAlumno">
                <a id="nuevaTareaLink" href="tareasPrioritarias.php">Tareas Prioritarias</a>
            </div>
            <div class="Examen">
                <a id="nuevaTareaLink" href="addExamen.php">Examen</a>
            </div>
        </div>
        <div id="parteMedio">
            <div id="contenido">
                <table id ="tabla">
                    <?php
                    include 'BaseXClient.php';
                    $bxc = new BaseXClient("localhost", 1984, "admin", "admin");
                    $bxc->ejecutar("CREATE DB database " . dirname(__FILE__) . "/PhpBaseX.xml");
                    $bxc->ejecutar("open database")
                    ?>
                    <tr>
                        <!-- Contenido de la tabla <td></td>-->   
                    </tr>
                    <?php
                    $table1 = 'for $a in ./empresa/tareas/tarea return
                <tr>
                    <td> {fn:string($a/fecha_limite)} </td>   <td> {fn:string($a/encargado)} </td>   <td> {fn:string($a/descripcio)} </td>   <td> {fn:string($a/prioridad)} </td>   <td> {fn:string($a/id)} </td>
                </tr>';
                    $table2 = $bxc->query($table1);
                    while ($table2->more()){
                        echo $table2->next();
                    }
                    $table2->close();
                    $bxc->close();
                    ?>
                </table>
            </div>
        </div>
        <div>
            <form method=post action="actualizaJefe.php">
                Nombre: <input type="text" name="Nombre" placeholder="nombre">
                <input type=submit value="Guardar Nombre">
            </form>
        </div>
    </body>
</html>
