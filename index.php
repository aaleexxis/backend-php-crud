<?php

$mensaje = "";
$error = "";
$nombre = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $edad = filter_input(INPUT_POST,"edad",FILTER_VALIDATE_INT);

    if ($nombre === "") {
        $error = "El nombre es obligatorio.";
    } elseif ($edad === false || $edad === null || $edad < 1 || $edad > 120) {
        $error = "Introduce una edad válida entre 1 y 120 años.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Introduce un correo electrónico válido.";
    } else {
        $nombreSeguro = htmlspecialchars($nombre,ENT_QUOTES,"UTF-8");
        $emailSeguro = htmlspecialchars($email,ENT_QUOTES,"UTF-8");

        if ($edad >= 18) {
            $situacion = "Eres mayor de edad.";
        } else {
            $situacion = "Eres menor de edad.";
        }

        $mensaje = "Hola, $nombreSeguro. Tienes $edad años. ". "$situacion". "<br>". "Tu correo es $emailSeguro.";
    }
}

?>

<?php if ($error !== "") { ?>
    <p><?php echo $error; ?></p>
<?php } ?>

<?php if ($mensaje !== "") { ?>
    <p><?php echo $mensaje; ?></p>
<?php } ?>