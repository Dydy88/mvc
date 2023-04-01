<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes annonces</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="/js/app.js" defer></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Mes annonces</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item active"><a class="nav-link" href="/">Accueil <span class="sr-only">(current)</span></a></li>

                <?php if ($_SESSION['user']['roles'] != null) : ?>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['roles'][0] == 'ROLE_ADMIN') : ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/index">Administration</a></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>
                    <li class="nav-item"> <a class="nav-link" href="/annonces/ajouter">Ajouter Annonces </a></li>
                <?php endif; ?>

                <li class="nav-item"><a class="nav-link" href="/annonces">Liste des annonces</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>

            </ul>

            <!-- Paramètre Compte Client  -->
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>

                    <li class="nav-item"><a class="nav-link" href="/users/logout">Déconnexion</a></li>
                <?php else : ?>
                    <li class="nav-item"><a class="nav-link" href="/users/login">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="/users/register/">Inscription</a></li>
                <?php endif; ?>
            </ul>


        </div>
    </nav>

    <div class="container">
        <!-- On affiche les Messages Contenu en session -->
        <?php if (!empty($_SESSION['erreur'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['erreur']; ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php unset($_SESSION['erreur']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['message'])) : ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['message']; ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"> </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

</body>

</html>