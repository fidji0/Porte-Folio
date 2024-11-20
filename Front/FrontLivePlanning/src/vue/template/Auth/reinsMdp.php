<?php
include_once DIRVUE . "/elements/head.php";
?>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="img/Planner.webp" class="img-fluid h-100 object-fit-cover" alt="Login image">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Modification du mot de passe</h1>
                                    </div>
                                    <form class="user" action="" id="form" method="post">
                                        <div class="form-floating mb-3">
                                            <input name="password" type="password" id="password" class="form-control" placeholder="Mot de passe" value="<?= isset($_POST['password']) ? $_POST['password'] : null ?>" required>
                                            <label for="password">Mot de passe</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input name="copyPassword" type="password" id="CopyPassword" class="form-control" placeholder="Confirmer le mot de passe" value="<?= isset($_POST['copyPassword']) ? $_POST['copyPassword'] : null ?>" required>
                                            <label for="CopyPassword">Confirmer le mot de passe</label>
                                        </div>
                                        <div class="d-none">
                                            <input name="uniqid" type="text" class="form-control" value="<?= isset($_GET['uniqid']) ? $_GET['uniqid'] : null ?>">
                                        </div>
                                        <div class="alert alert-danger <?= isset($error) ?: "d-none" ?>" role="alert">
                                            <?= !isset($error) ?: $error ?>
                                        </div>
                                        <div class="alert alert-success <?= isset($success) ?: "d-none" ?>" role="alert">
                                            <?= !isset($success) ?: $success ?>
                                        </div>
                                        <div class="container" style="font-size: 0.9rem;">
                                            <p>Le mot de passe doit contenir :</p>
                                            <ul class="ps-4">
                                                <li id="length" style="color: red;">8 caractères minimum</li>
                                                <li id="maj" style="color: red;">1 Majuscule</li>
                                                <li id="min" style="color: red;">1 Minuscule</li>
                                                <li id="number" style="color: red;">1 Chiffre</li>
                                                <li id="spe" style="color: red;">1 Caractère spécial @$!%*#?&</li>
                                                <li id="identique" style="color: red;">Les mots de passe doivent être identiques</li>
                                            </ul>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 py-2">Enregistrer</button>
                                        <div class="alert w-100 mt-3 <?= isset($success) && $success === true ? "alert-success" : (isset($error) && $error === true ? "alert-danger" : "d-none") ?>" role="alert">
                                            <?= $message ?>
                                        </div>
                                    </form>
                                    <div class="text-center mt-3">
                                        <a class="small" href="/login">Se connecter</a>
                                    </div>
                                    <div class="text-center mt-2">
                                        <a class="small" href="/register">Pas encore inscrit ? Inscrivez-vous</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let password = document.querySelector("#password");
        let password2 = document.querySelector("#CopyPassword");

        password.addEventListener("input", () => verifyPassword());
        password2.addEventListener("input", () => verifyPassword());

        function verifyPassword() {
            let maj = document.querySelector("#maj");
            let min = document.querySelector("#min");
            let number = document.querySelector("#number");
            let spe = document.querySelector("#spe");
            let length = document.querySelector("#length");
            let identique = document.querySelector("#identique");

            const regexMajuscule = /[A-Z]/;
            const regexMinuscule = /[a-z]/;
            const regexNumber = /[0-9]/;
            const regexCaractereSpecial = /[@$!%*#?&]/;

            maj.style.color = regexMajuscule.test(password.value) ? "green" : "red";
            min.style.color = regexMinuscule.test(password.value) ? "green" : "red";
            number.style.color = regexNumber.test(password.value) ? "green" : "red";
            spe.style.color = regexCaractereSpecial.test(password.value) ? "green" : "red";
            length.style.color = password.value.length >= 8 ? "green" : "red";
            identique.style.color = password.value === password2.value ? "green" : "red";

            return regexCaractereSpecial.test(password.value) && password.value.length >= 8 && regexNumber.test(password.value) &&
                regexMajuscule.test(password.value) && regexMinuscule.test(password.value);
        }

        let form = document.querySelector("#form");
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            if (verifyPassword() && password.value === password2.value) {
                form.submit();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
