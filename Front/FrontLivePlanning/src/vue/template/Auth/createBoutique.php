<?php
include_once DIRVUE . "/elements/head.php";
?>

<body class="bg2" style="display: flex;
      align-items: center;
      justify-content: center;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5 bg1 ">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Enregistrement de votre commerce</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <select class="form-control " style="border-radius: 10rem; " name="type_activite" required>

                                                <option>Selectionnez le type de commerce</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "boutique" ? "selected" : null ?> value="boutique">Commerce</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "restaurant" ? "selected" : null ?> value="restaurant">Restaurant</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "artisan" ? "selected" : null ?> value="artisan">Artisan</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "service" ? "selected" : null ?> value="service">Service</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "createur" ? "selected" : null ?> value="createur">Créateur</option>
                                                <option <?= isset($_POST['type_activite']) && $_POST['type_activite'] === "association" ? "selected" : null ?> value="association">Association</option>


                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control " style="border-radius: 10rem; " name="itinerant" required>

                                                <option>Êtes vous itinérant</option>
                                                <option <?= isset($_POST['itinerant']) && $_POST['itinerant'] === "boutique" ? "selected" : null ?> value="0">Non</option>
                                                <option <?= isset($_POST['itinerant']) && $_POST['itinerant'] === "restaurant" ? "selected" : null ?> value="1">Oui</option>


                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" value="<?= isset($_POST['social']) ? $_POST['social'] : null ?>" name="social" class="form-control form-control-user" id="DS" placeholder="Dénomination sociale" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?= isset($_POST['boutiqueName']) ? $_POST['boutiqueName'] : null ?>" name="boutiqueName" class="form-control form-control-user" id="boutiqueName" placeholder="Nom d'enseigne" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?= isset($_POST['responsable']) ? $_POST['responsable'] : null ?>" name="responsable" class="form-control form-control-user" id="responsable" placeholder="Nom du responsable" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" value="<?= isset($_POST['siret']) ? $_POST['siret'] : null ?>" name="siret" class="form-control form-control-user" id="siret" placeholder="Numéro de siret" required autocomplete="off">
                                            <?php 
                                            if($errorSiret):
                                                ?>
                                            <div class="">
                                                <p class="small text-danger" >Le siret doit contenir 14 caractères</p>
                                            </div>
                                            <?php endif ?>
                                        </div>
                                        <div class="form-group" style="position: relative;">
                                            <input type="text" id="autocompleteInput" <?= isset($_POST['adressComplete']) ? " value='" . $_POST['adressComplete'] . "'" : null ?> name="adressComplete" class="form-control form-control-user" placeholder="Adresse principale" required autocomplete="off">
                                            <ul class="list-group position-absolute" id="autocomplete-list" style="right : 0; top : 48px; width : 100%;">
                                            </ul>
                                        </div>
      
                                        <div class="form-group d-none">
                                        <input type="text" id="address" <?= isset($_POST['adress']) ? " value='" . $_POST['adress'] . "'" : null ?> name="adress" class="form-control form-control-user" placeholder="Adresse principale" required autocomplete="off">

                                            <input type="text" id="zipCode" value="<?= isset($_POST['zipCode']) ? $_POST['zipCode'] : null ?>" name="zipCode" class="form-control form-control-user" id="inputUsername" placeholder="Code Postal" required>
                                        </div>
                                        <div class="form-group d-none">
                                            <input type="text" id="city" value="<?= isset($_POST['city']) ? $_POST['city'] : null ?>" name="city" class="form-control form-control-user" id="inputUsername" placeholder="Ville" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" value="<?= isset($_POST['contactEmail']) ? $_POST['contactEmail'] : null ?>" name="contactEmail" class="form-control form-control-user" id="inputUsername" placeholder="Email de contact" required>
                                            <?php 
                                            if($errorMail):
                                                ?>
                                            <div class="text-center">
                                                <p class="small danger" >L'adresse e-mail n'est pas valide.</p>
                                            </div>
                                            <?php endif ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="accept" id="accept" <?= isset($_POST['accept']) ? "checked" : null ?> required>
                                            <label for="accept">J'accepte les conditions <a href="/cgu" target="_blank">générales d'utilisation</a> </label>
                                        </div>
                                        <?php
                                        if (isset($error)) {

                                        ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $error ?>
                                            </div>
                                        <?php  }
                                        ?>
                                        <button class="btn btn-primary btn-user btn-block" type="submit">
                                            Enregistrer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <script>
        function setCookie(nom, valeur, expirationJours) {
            var dateExpiration = new Date();
            dateExpiration.setTime(dateExpiration.getTime() + (expirationJours * 24 * 60 * 60 * 1000));
            var expiration = "expires=" + dateExpiration.toUTCString();
            document.cookie = nom + "=" + valeur + ";" + expiration + ";path=/";
        }


        var inputElement = document.getElementById('autocompleteInput');
        var address = document.getElementById('address');
        var zipCode = document.getElementById('zipCode');
        var city = document.getElementById('city');

        var apiUrl = 'https://api-adresse.data.gouv.fr/search/?';

        if (inputElement) {

            inputElement.addEventListener('input', function() {
                var keyword = inputElement.value;
                if (keyword.length > 2) {


                    // Effectuer la requête API en utilisant fetch()
                    fetch(apiUrl + 'q=' + encodeURIComponent(keyword) + "&limit=5")
                        .then(response => response.json())
                        .then(data => {
                            // Gérer les résultats de l'API

                            var suggestions = data.features.map(item => ({
                                "name": item.properties.label,
                                "adress": item.properties.name,
                                "city": item.properties.city,
                                "zipCode": item.properties.postcode,
                                "latitude": item.geometry.coordinates[1],
                                "longitude": item.geometry.coordinates[0]
                            }));
                            //

                            //// Afficher les suggestions dans une liste
                            var suggestionsList = document.getElementById('autocomplete-list');
                            suggestionsList.innerHTML = '';



                            suggestions.forEach(suggestion => {
                                // Créer un élément d'option
                                var li = document.createElement('li');

                                // Définir la valeur de l'option
                                li.textContent = suggestion.name;

                                // Ajouter des attributs de données
                                li.className = "list-group-item"
                                li.setAttribute('data-longitude', suggestion.longitude);
                                li.setAttribute('data-latitude', suggestion.latitude);

                                li.addEventListener('click', function() {
                                    // Réagir au clic sur une suggestion
                                    zipCode.value = suggestion.zipCode
                                    city.value = suggestion.city
                                    address.value = suggestion.adress
                                    inputElement.value = suggestion.name

                                    suggestionsList.innerHTML = ''; // Effacer la liste après avoir sélectionné
                                    setCookie('latitude', suggestion.latitude, 100)
                                    setCookie('longitude', suggestion.longitude, 100)
                                    return;
                                });

                                // Ajouter l'option à datalist
                                suggestionsList.appendChild(li);
                            });
                        })
                        .catch(error => {
                            console.log('Erreur lors de la requête API :', error);
                        });
                }
            });
        }
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>