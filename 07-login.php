<?php

$co = "";
$message = "";

// echo (password_hash("MotDePasse", PASSWORD_DEFAULT));

if (
    isset($_POST["login"]) &&
    isset($_POST["password"]) &&
    isset($_POST["conf-password"]) &&
    strlen($_POST["login"]) > 0 &&
    strlen($_POST["password"]) > 0 &&
    strlen($_POST["conf-password"]) > 0 
) {

    $login = trim(htmlspecialchars($_POST["login"]), " ");
    $password = trim(htmlspecialchars($_POST["password"]), " ");
    $conf = trim(htmlspecialchars($_POST["conf-password"]), " ");

    if ($password === $conf) {

        $connexion = new PDO("mysql:host=localhost;dbname=backoffice;charset=utf8", "root", "");
        $sth = $connexion->prepare("SELECT id, password FROM user WHERE login = :login");
        $sth->execute(["login" => $login]);
        $user = current($sth->fetchAll(PDO::FETCH_ASSOC));

        if (is_array($user)) {
            $verif = password_verify($password, $user["password"]);

            if ($verif) {
                $message = "<div class=\"alert alert-success\">
                                <h4>Connecté</h4>
                            </div>";
            } else {
                $message = "<div class=\"alert alert-danger\">
                                <h4>Erreur</h4>
                            </div>";
            }
        } else {
            $message = "<div class=\"alert alert-danger\">
                            <h4>Erreur</h4>
                        </div>";
        }
    } else {
        $message = "<div class=\"alert alert-danger\">
                        <h4>Les mots de passes sont différents !</h4>
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="bg-warning">
        <nav class="container navbar navbar-expand navbar-light px-3">
            <a href="" class="navbar-brand">Page de Connexion</a>
        </nav>
    </header>
    <main class="container mt-3">
        <form method="post">
            <h1>Accéder au BackOffice</h1>
            <div class="container">
                <?= $message ?>
            </div>
            <div class="mb-3 col-4 offset-4">
                <label class="form-label" for="login">Login</label>
                <input class="form-control" type="text" name="login" id="login">
            </div>
            <div class="mb-3 col-4 offset-4">
                <label class="form-label" for="password">Mot de passe</label>
                <input class="form-control" type="password" name="password" id="password">
            </div>
            <div class="mb-3 col-4 offset-4">
                <label class="form-label" for="conf-password">Confirmation Mot de passe</label>
                <input class="form-control" type="password" name="conf-password" id="conf-password">
            </div>
            <button type="submit" class="btn btn-primary col offset-4 mb-3">Envoyer</button>
        </form>
    </main>
</body>

</html>