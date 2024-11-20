<?php include_once DIRVUE."/elements/head.php"; ?>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="img/Planner.webp" class="img-fluid h-100 object-fit-cover" alt="Login image">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Connexion Espace PRO</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Nom d'utilisateur ou e-mail">
                                            <label for="inputUsername">Nom d'utilisateur ou e-mail</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" name="password" class="form-control" id="InputPassword" placeholder="Mot de passe">
                                            <label for="InputPassword">Mot de passe</label>
                                        </div>
                                        <?php if (isset($error)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $error === true ? "Identifiant ou mot de passe invalide" : $error ?>
                                        </div>
                                        <?php endif; ?>
                                        <button class="btn btn-primary w-100 py-2 mb-3" type="submit">Se connecter</button>
                                    </form>
                                    <div class="text-center">
                                        <a class="small" href="/mdp">Mot de passe oublié?</a>
                                    </div>
                                    <div class="text-center mt-2">
                                        <a class="small" href="/register">Pas encore inscrit? Inscrivez-vous</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>