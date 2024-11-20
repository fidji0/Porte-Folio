<?php
include_once DIRVUE . "/elements/head.php";
?>

<body class="bg2" style="height: 100vh;display: flex;
      align-items: center;
      justify-content: center;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5 bg1" >
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Choix de l'acteur'</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <select class="form-control  form-control-lg" style="border-radius: 10rem; " name="selectedIdBoutique">
                                                <?php
                                                $y = 0;
                                                foreach ($res as $key => $value) {
                                                   
                                                ?>
                                                    <option <?= $y === 0 ? "selected" : null ?> value="<?= base64_encode(json_encode(["idBoutique" => $value['id'] , "idDolibarr" => $value['id_dolibarr']])) ?>"><?= $value['name'] ?></option>
                                                <?php
                                                $y++;
                                                }
                                                ?>
                                                
                                            </select>
                                        </div>
                                        <button class="btn btn-primary btn-lg btn-block" style="border-radius: 10rem; " type="submit">
                                            Je choisis cet acteur local
                                        </button>
                                        <a class="btn btn-success btn-lg btn-block" style="border-radius: 10rem; " href="createBoutique">
                                            J'enregistre un nouvel acteur local
                                        </a>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>