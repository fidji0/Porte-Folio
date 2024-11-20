<?php
include_once DIRVUE . "/elements/head.php";

?>
  <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f0f5ff;
        }
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--secondary-color);
            line-height: 1.6;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color);
        }
        .assistance-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card-body {
            padding: 40px;
            background-color: #ffffff;
        }
        .contact-info {
            background-color: var(--light-bg);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .contact-info i {
            color: var(--primary-color);
            margin-right: 10px;
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
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }
    </style>

<body>
<div id="wrapper" class="d-flex flex-row">
<?php include_once DIRVUE . "/elements/sideBar.php"; ?>
    <div class="assistance-container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Besoin d'assistance ?</h2>
            </div>
            <div class="card-body">
                <h4 class="mb-4 text-primary">Nous sommes là pour vous aider</h4>
                
                <div class="contact-info mb-4">
                    <h5><i class="fas fa-phone"></i>Assistance téléphonique</h5>
                    <p>Du lundi au vendredi : 0451220170 ou 0669004920</p>
                    <ul>
                        <li>9h00 - 12h00</li>
                        <li>14h00 - 17h30</li>
                    </ul>
                </div>

                <div class="contact-info mb-4">
                    <h5><i class="fas fa-envelope"></i>Assistance par e-mail (24h ouvrées)</h5>
                    <p>Disponible 24h/24, 7j/7</p>
                    <a href="mailto:contact@livelanning.fr" class="text-primary">contact@liveplanning.fr</a>
                </div>

                <p class="mb-4">Notre équipe d'experts est prête à répondre à toutes vos questions concernant Live Planning. N'hésitez pas à nous contacter pour obtenir de l'aide sur l'utilisation de notre plateforme, la configuration de votre compte, ou pour toute autre demande.</p>

                <div class="text-center">
                    <a href="mailto:contact@liveproxim.fr" class="btn btn-primary">Nous contacter par e-mail</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once DIRVUE . "/elements/footer.php"; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>