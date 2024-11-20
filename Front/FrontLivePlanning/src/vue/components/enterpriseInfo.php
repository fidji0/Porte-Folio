<h2 class="mt-5 mb-4 text-center">Informations de la Société</h2>
            <div class="employee-card card" style="border-left-color: red;">
                <div class="card-body">
                    <h5 class="card-title">Société : <?= $_SESSION["boutique"]["social"] ?></h5>
                    <p class="card-text">Nom de la boutique : <?= $_SESSION["boutique"]["boutiqueName"] ?></p>
                    <p class="card-text">Code entreprise : <?= $_SESSION["boutique"]["ste_code"] ?></p>
                    <p class="card-text">SIRET : <?= $_SESSION["boutique"]["siret"] ?></p>
                    <p class="card-text">Adresse : <?= $_SESSION["boutique"]["adress"] . " " . $_SESSION["boutique"]["zipCode"] . " " . $_SESSION["boutique"]["city"] ?></p>
                    <p class="card-text">Téléphone : <?= $_SESSION["boutique"]["phoneNumber"] ?></p>
                    <p class="card-text">Email : <?= $_SESSION["boutique"]["email"] ?></p>
                    <p class="card-text">Nombre maximum d'utilisateurs : <?= $_SESSION["boutique"]["nbr_max_user"] ?></p>
                </div>
            </div>