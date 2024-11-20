<?php
include_once DIRVUE . "/elements/head.php";

?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '445876881841636');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=445876881841636&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
        }
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--secondary-color);
            line-height: 1.6;
            background-color: var(--light-bg);
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color);
        }
        .registration-container {
            max-width: 800px;
            margin: 80px auto;
        }
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10rem;
            padding: 12px 20px;
            font-size: 0.9rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            border-color: var(--primary-color);
        }
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 10rem;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-2px);
        }
        .password-requirements {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .password-requirements span {
            display: block;
            margin-bottom: 5px;
        }
        .password-requirements .valid {
            color: #28a745;
        }
        .password-requirements .invalid {
            color: #dc3545;
        }
    </style>
<body style="background-color: #f0f5ff; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Arial', sans-serif;">
<nav class="navbar navbar-expand-lg navbar-light fixed-top ">
        <div class="container">
            <a class="navbar-brand" href="/">Live Planning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
        </div>
    </nav>    
<div class="container my-5">
        <div class="card shadow-lg" style="max-width: 800px; margin: auto; border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2 class="h3 mb-0">Je m'enregistre</h2>
            </div>
            <div class="card-body" style="background-color: #ffffff;">
                <form class="user" action="" id="form" method="post">
                <div class="alert mt-4 <?= isset($succes) && $succes === true ? "alert-success" : (isset($error) && $error === true ? "alert-danger" : "d-none") ?>" role="alert">
                        <?= ($message ?? '' ).( isset($succes) && $succes === true ? "<a href='/login'> Rejoindre la page de connexion en cliquant ici </a>" : "" ) ?>
                    </div>
                    <!-- Informations personnelles -->
                    <div class="mb-4">
                        <input type="email" name="email" class="form-control form-control-user" placeholder="Email de connexion" value="<?= isset($_POST['email']) ? $_POST['email'] : null ?>" required>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <input name="password" type="password" id="password" class="form-control form-control-user" placeholder="Mot de passe" value="<?= isset($_POST['password']) ? $_POST['password'] : null ?>" required>
                        </div>
                        <div class="col-md-6">
                            <input name="copyPassword" type="password" id="CopyPassword" class="form-control form-control-user" placeholder="Confirmer le mot de passe" value="<?= isset($_POST['copyPassword']) ? $_POST['copyPassword'] : null ?>" required>
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
                        
                    </div>
                    
                    <!-- Informations du commerce -->
                    <hr class="my-4" style="border-top: 2px solid #e0e0e0;">
                    <h4 class="text-primary mb-4">Informations de l'entreprise</h4>
                    <div class="mb-4">
                        <input type="text" value="<?= isset($_POST['ste_code']) ? $_POST['ste_code'] : null ?>" name="ste_code" class="form-control form-control-user" id="ste_code" placeholder="Code entreprise sert a identifier vos salariés" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" value="<?= isset($_POST['social']) ? $_POST['social'] : null ?>" name="social" class="form-control form-control-user" id="DS" placeholder="Dénomination sociale" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" value="<?= isset($_POST['boutiqueName']) ? $_POST['boutiqueName'] : null ?>" name="boutiqueName" class="form-control form-control-user" id="boutiqueName" placeholder="Nom d'enseigne" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" value="<?= isset($_POST['siret']) ? $_POST['siret'] : null ?>" name="siret" class="form-control form-control-user" id="siret" placeholder="Numéro de siret" required autocomplete="off">
                    </div>
                    <div class="mb-4" style="position: relative;">
                        <input type="text" id="autocompleteInput" <?= isset($_POST['adress']) ? " value='" . $_POST['adress'] . "'" : null ?> name="adress" class="form-control form-control-user" placeholder="Adresse principale" required autocomplete="off">
                        <ul class="list-group position-absolute" id="autocomplete-list" style="right: 0; top: 100%; width: 100%; z-index: 1000;"></ul>
                    </div>
                    <div class="mb-4" style="position: relative;">
                        <input type="text" id="autocompleteInput" <?= isset($_POST['zipCode']) ? " value='" . $_POST['zipCode'] . "'" : null ?> name="zipCode" class="form-control form-control-user" placeholder="Code Postal" required autocomplete="off">
                        <ul class="list-group position-absolute" id="autocomplete-list" style="right: 0; top: 100%; width: 100%; z-index: 1000;"></ul>
                    </div>
                    <div class="mb-4" style="position: relative;">
                        <input type="text" id="autocompleteInput" <?= isset($_POST['city']) ? " value='" . $_POST['city'] . "'" : null ?> name="city" class="form-control form-control-user" placeholder="Ville" required autocomplete="off">
                        <ul class="list-group position-absolute" id="autocomplete-list" style="right: 0; top: 100%; width: 100%; z-index: 1000;"></ul>
                    </div>
                    
                    
                    <div class="mb-4">
                        <input type="tel" value="<?= isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null ?>" name="phoneNumber" class="form-control form-control-user" placeholder="Numéro de téléphone">
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" name="accept" id="accept" class="form-check-input" <?= isset($_POST['accept']) ? "checked" : null ?> required>
                        <label class="form-check-label" for="accept">J'accepte les <a href="/cgu" target="_blank" class="text-primary">conditions générales d'utilisation</a></label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block py-2">
                        Enregistrer
                    </button>
                    
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control-user {
            border-radius: 10rem;
            padding: 1rem;
            font-size: 0.9rem;
        }
        .btn-user {
            border-radius: 10rem;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
        }
        .text-primary {
            color: #4e73df !important;
        }
        .bg-primary {
            background-color: #4e73df !important;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
    </style>
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
            identique.style.color = password.value == password2.value ? "green" : "red";
            
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

    <!-- Bootstrap core JavaScript-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>

</html>