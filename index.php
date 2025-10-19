<?php
require_once 'precios.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Electrónica</title>
    <link rel="stylesheet" href="style.css"> // llamamos al css
</head>
<body>

<h2>Selecciona productos de electrónica</h2>

<form action="procesar.php" method="POST"> // para enviar datos a clase procesar con método POST

    <?php  //array asociativo
    $precios = [
        "Auriculares inalámbricos" => 50,
        "Teclado mecánico" => 70,
        "Mouse gamer" => 40,
        "Consola portátil" => 200,
        "Tarjeta de regalo digital" => 25
    ];

    /* for each ($precios (nombre del aaray) as
         $producto (nombre de los productos = $precios = cantidad de precio*/
    foreach($precios as $producto => $precio): ?>


        /* ahora viene el bloque contenedor (div), esto se usa para agrupar elementos relacionados
        foreach*/
        <div class="producto">
            <label> // sirve para poner texto descriptivo a un input

                /* ESTOY PONIENDO LO QUE SIGNIFICA CADA PARTE DE ABAJO.
                crea la casilla que puedes marcar |
                guarda los productos seleccionados en un array llamado productos |
                el valor que se envía si lo marcas (por ejemplo, “Auriculares inalámbricos”).*/

                <input type="checkbox" name="productos[]" value="<?= $producto ?>">
                <?= $producto ?> - <?= number_format($precio, 2) ?>€

            </label>
            <br>

            // aqui es lo mismo pero para cantidad y descuento y al ser numeros pone minimo y maximo que hay que poner
            Cantidad: <input type="number" name="cantidad[<?= $producto ?>]" min="1" value="1">
            Descuento: <input type="number" name="descuento[<?= $producto ?>]" min="0" max="100" value="0"> %
        </div>
    <?php endforeach; ?>
 // lo mismo para el IVAA boton input
    <label>IVA (%):</label>
    <input type="number" name="iva" value="21" min="0" max="100"><br><br>


    // boton de añdir al carrito
    <button type="submit" class="add">Añadir al carrito</button>

</form>

</body>
</html>
