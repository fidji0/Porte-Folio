<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <title>Live Planning - Tarifs</title>

    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <!-- Meta description SEO -->
    <meta name="description" content="Découvrez les plans tarifaires de Live Planning et choisissez celui qui correspond à vos besoins. Simplifiez vos plannings et vos paies.">

    <!-- Meta keywords SEO -->
    <meta name="keywords" content="gestion de planning, tarifs, TPE, PME, restaurants, artisans, commerces, planification, gestion RH, Live Planning">

    <!-- Bootstrap & Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --success-color: #2ecc71;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: var(--secondary-color);
            line-height: 1.6;
            background-color: var(--light-bg);
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color);
        }

        .nav-link {
            color: var(--secondary-color);
        }

        .hero {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 60px 0;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .price-card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .price-card:hover {
            transform: translateY(-5px);
        }

        .price-card h3 {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 20px;
        }

        .price-card .price {
            font-size: 36px;
            font-weight: bold;
            color: var(--accent-color);
        }

        .price-card ul {
            list-style: none;
            padding: 0;
            text-align: left;
        }

        .price-card ul li {
            font-size: 16px;
            padding: 10px 0;
        }

        .price-card ul li i {
            color: var(--success-color);
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            padding: 12px 30px;
        }

        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 50px 0;
        }

        footer a {
            color: #bdc3c7;
        }

        footer a:hover {
            color: white;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Live Planning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mailto:contact@liveplanning.fr">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero text-center">
        <div class="container">
            <h1>Découvrez nos tarifs</h1>
            <p class="lead">Choisissez le plan qui correspond à vos besoins et commencez dès aujourd'hui à simplifier la gestion de vos plannings.</p>
        </div>
    </section>

    <!-- Section Tarifs -->
    <section id="pricing" class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="price-card card p-4">
                        <h3>Petite équipe</h3>
                        <p>Idéal pour les petites équipes jusqu'à 5 personnes.</p>
                        <div class="price">30€/mois</div>
                        <ul class="mt-4">
                            <li><i class="fas fa-check"></i>Jusqu'à 5 utilisateurs</li>
                            <li><i class="fas fa-check"></i>Gestion de planning simplifiée</li>
                            <li><i class="fas fa-check"></i>Statistiques en direct</li>
                            <li><i class="fas fa-check"></i>Application mobile</li>
                            <li><i class="fas fa-check"></i>Export pour la paie</li>
                            <li><i class="fas fa-check"></i>Support par email et téléphone</li>
                        </ul>
                        <a href="/convert" class="btn btn-primary mt-4">Essayez 1 mois gratuitement</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="price-card card p-4">
                        <h3>Équipe jusqu'à 10 personnes</h3>
                        <p>Parfait pour les équipes qui souhaitent optimiser leur gestion des plannings.</p>
                        <div class="price">45€/mois</div>
                        <ul class="mt-4">
                            <li><i class="fas fa-check"></i>Jusqu'à 10 utilisateurs</li>
                            <li><i class="fas fa-check"></i>Gestion de planning simplifiée</li>
                            <li><i class="fas fa-check"></i>Statistiques en direct</li>
                            <li><i class="fas fa-check"></i>Application mobile</li>
                            <li><i class="fas fa-check"></i>Export pour la paie</li>
                            <li><i class="fas fa-check"></i>Support par email et téléphone</li>
                        </ul>
                        <a href="/convert" class="btn btn-primary mt-4">Essayez 1 mois gratuitement</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="price-card card p-4">
                        <h3>Sur mesure</h3>
                        <p>Pour une gestion de planning plus complexe avec des impératifs de temps ou de compétences.</p>
                        <div class="price">Sur devis</div>
                        <ul class="mt-4">
                            <li><i class="fas fa-check"></i>Gestion d'équipes avec rôles</li>
                            <li><i class="fas fa-check"></i>Gestion des tâches par compétences</li>
                            <li><i class="fas fa-check"></i>Réglementation spécifique (diplôme, compétences)</li>
                            <li><i class="fas fa-check"></i>Gestion du nombre de personnel par tâche</li>
                            <li><i class="fas fa-check"></i>Et bien plus encore...</li>
                        </ul>
                        <a href="#" class="btn btn-primary mt-4">Prendre rendez-vous</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Live Planning</h5>
                    <p>Solution de gestion de planning pour TPE et PME</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="#pricing">Tarifs</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Nous contacter</h5>
                    <p>Email : contact@liveplanning.fr</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
