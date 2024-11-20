<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <title>Réinitialisation de mot de passe</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #fafafa; /* Couleur d'arrière-plan claire */
            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
        }

        .header h1 {
            font-size: 32px;
            color: #0051A6; /* Bleu principal de votre site */
            font-weight: 700;
            margin: 0;
        }

        .content {
            text-align: left;
            padding: 20px;
            color: #333;
        }

        .content h1 {
            font-size: 24px;
            color: #0051A6; /* Bleu pour les titres */
            font-weight: 700;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .cta {
            text-align: center;
            margin-top: 30px;
        }

        .cta a {
            background-color: #00AEEF; /* Bleu turquoise utilisé sur le site */
            color: #ffffff;
            text-decoration: none;
            padding: 15px 25px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 18px;
        }

        .cta a:hover {
            background-color: #008bbf;
        }

        .footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icons img {
            width: 32px;
            margin: 0 10px;
            display: inline-block;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .content h1 {
                font-size: 22px;
            }

            .content p {
                font-size: 14px;
            }

            .cta a {
                padding: 12px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>LivePlanning</h1> <!-- Remplacement du logo par le texte LivePlanning -->
        </div>

        <div class="content">
            <h1>Réinitialisation de votre mot de passe</h1>
            <p>Bonjour,</p>
            <p>
                Il semble que vous ayez demandé à réinitialiser votre mot de passe. Pour ce faire, veuillez cliquer sur le lien ci-dessous :
            </p>
        </div>

        <div class="cta">
            <a href="<?= $checkOut ?>?token=<?= $code ?>" target="_blank" rel="noopener">Réinitialiser mon mot de passe</a>
        </div>

        <div class="footer">
        <p>© <?= date('Y') ?> Tous droits réservés - LivePlanning</p>

        </div>
    </div>

</body>

</html>
