<?php


// esto es seguridad basica para comprobar si la pagina fie abierta con post
// desde el formulario(index), evita acceder escribiendo la utl directamente.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

// Carga el archivo donde están definidos los precios de los productos.
require_once 'precios.php';

// Reconstruir carrito desde POST, ISSET COMPRUEBA SI HA ELEGIDO ALGO DEL CARRITO
$carrito = []; // creas el carrito
if (isset($_POST['productos'])) { /* aqui compruebas si el usuario le dio al checkbox
    por que asi si le dio entras al foreach de productos y recorres todo lo que tiene */

    foreach ($_POST['productos'] as $producto) {
        $cantidad = $_POST['cantidad'][$producto] ?? 1;
        $descuento = $_POST['descuento'][$producto] ?? 0;
        $carrito[] = [
            'producto' => $producto,
            'cantidad' => (int)$cantidad,
            'precio_unitario' => $precios[$producto],
            'descuento' => (int)$descuento
        ];
    }
}

// Eliminar producto
if (isset($_POST['eliminar'])) { /*Comprueba con ISSET si se ha dado al boton
    eliminar*/

    unset($carrito[$_POST['eliminar']]); /*unset elimina del array $carrito el producto con
    ese indice, es decir la posicion del prodcuto */

    $carrito = array_values($carrito);
    /* reindexar. es para ordenar de nuevo las posciones
 del carrito es decir si eliminaste posicion 1, y tenias 0, 1 y 2, la 1 se queda vacia
 entonces reindexar lo que hace es que las posiciones se organicen otra vez, ahora la 2
 toma el puesto de la 1, es decir empiezan desde 0 otra vez*/
}
// Compruena si ha seleccionado IVA
$iva = isset($_POST['iva']) ? (float)$_POST['iva'] : 21;
?>

// abrimos html una vez que ha comprobado todo, para crear el carrito en vista
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Electrónica</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

<h2>Carrito de compras - Electrónica</h2>

//La primera linea comprueba si el carrito esta vacio
// si lo sale un mensaje de No hay productos en el carrito
<?php if (empty($carrito)): ?>
    <p>No hay productos en el carrito.</p>

// Si hay muestra los productos de carrito
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0"> // espacio entre celdas
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Descuento</th>
            <th>Total</th>
            <th>Acción</th>
        </tr>
        <?php
        $subtotalGeneral = 0;

        // $index El número de posición del producto dentro del carrito (0, 1, 2, …).
        // $item El contenido completo del producto (un array con sus datos: nombre, cantidad, precio y descuento).
        foreach ($carrito as $index => $item):

            // Aqui hacemos los calculos del carrito
            $subtotal = $item['cantidad'] * $item['precio_unitario'];
            $descuentoAplicado = $subtotal * ($item['descuento'] / 100);
            $totalConDescuento = $subtotal - $descuentoAplicado;
            $subtotalGeneral += $totalConDescuento;
            ?>
        /*mostramos todos los resultados despues de seleccionar todos los productos y darle
            "añadir al carrito", y que se hayan procesado las operaciones*/
            <tr>
                <td><?= $item['producto'] ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td><?= number_format($item['precio_unitario'], 2) ?>€</td>
                <td><?= $item['descuento'] ?>%</td>
                <td><?= number_format($totalConDescuento, 2) ?>€</td>
                <td>
                    //aquí es para volver al carrito y elimina el producto que es has seleccionado
                    // es invisible para el usuario
                    <form method="POST" action="procesar.php">
                        <?php foreach ($carrito as $i => $c): ?>

                        //hidden = campo invisible usado para enviar información al servidor sin mostrarla al usuario.
                            <input type="hidden" name="productos[]" value="<?= $c['producto'] ?>">
                            <input type="hidden" name="cantidad[<?= $c['producto'] ?>]" value="<?= $c['cantidad'] ?>">
                            <input type="hidden" name="descuento[<?= $c['producto'] ?>]" value="<?= $c['descuento'] ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="iva" value="<?= $iva ?>">
                        <button type="submit" name="eliminar" value="<?= $index ?>">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

// Calculas el iva y se lo añdes al subtotal
    <?php
    $ivaTotal = $subtotalGeneral * ($iva / 100);
    $totalFinal = $subtotalGeneral + $ivaTotal;
    ?>
// Luego muestras todo el subtotal, el añadido del iva y el total fianl.
    <div class="totales">
        <h3>Subtotal (con descuentos): <?= number_format($subtotalGeneral, 2) ?>€</h3>
        <h3>IVA (<?= $iva ?>%): <?= number_format($ivaTotal, 2) ?>€</h3>
        <h2>Total Final: <?= number_format($totalFinal, 2) ?>€</h2>
    </div>
<?php endif; ?>


/*cuando le das al boton seguir comprando vuelve al formulario inicial (index)
y envia todo el contenido actual del carrito en campos ocultos (hidden), para no perder
lo que tienes en el.
*/
<form action="index.php" method="POST" style="margin-top: 15px;">
    <?php foreach ($carrito as $c): ?>
        <input type="hidden" name="productos[]" value="<?= $c['producto'] ?>">
        <input type="hidden" name="cantidad[<?= $c['producto'] ?>]" value="<?= $c['cantidad'] ?>">
        <input type="hidden" name="descuento[<?= $c['producto'] ?>]" value="<?= $c['descuento'] ?>">
    <?php endforeach; ?>
    <input type="hidden" name="iva" value="<?= $iva ?>">
    <button type="submit">Seguir comprando</button>
</form>



</body>
</html>