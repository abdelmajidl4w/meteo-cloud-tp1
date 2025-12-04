<?php
$apiKey = "f47b91ac11d66ff45d09c48179ab0d13"; 
$cities = ["Rabat", "Casablanca", "Paris", "London", "Tokyo", "New York", "Moscow"];
$weatherData = null;
$error = null;


if (isset($_POST['city'])) {
    $selectedCity = htmlspecialchars($_POST['city']);
    
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($selectedCity) . "&units=metric&lang=fr&appid=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 

    $response = curl_exec($ch);
    
    if(curl_errno($ch)){
        $error = 'Erreur cURL : ' . curl_error($ch);
    } else {
        
        if ($response) {
            $weatherData = json_decode($response, true);
            
            if (isset($weatherData['cod']) && $weatherData['cod'] != 200) {
                $error = "Erreur API : " . $weatherData['message'];
                $weatherData = null;
            }
        } else {
            $error = "Réponse vide de l'API.";
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo TP2</title>
    <style>
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        h1 { color: #333; margin-bottom: 1.5rem; font-size: 1.5rem; }
        select, button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            width: 100%;
            font-size: 1rem;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background-color: #0056b3; }
        
        .weather-card {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            animation: fadeIn 0.5s;
        }
        .temp {
            font-size: 3rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .desc {
            font-size: 1.2rem;
            color: #7f8c8d;
            text-transform: capitalize;
        }
        .details {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
            color: #555;
            font-size: 0.9rem;
        }
        .icon { width: 100px; height: 100px; }
        .error { color: red; margin-top: 10px; font-size: 0.9rem;}

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🌤️ Météo Express</h1>
    
    <form method="POST">
        <select name="city" required>
            <option value="" disabled selected>Choisir une ville...</option>
            <?php foreach ($cities as $city): ?>
                <option value="<?= $city ?>" <?= (isset($selectedCity) && $selectedCity === $city) ? 'selected' : '' ?>>
                    <?= $city ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Voir la météo</button>
    </form>

    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($weatherData): ?>
        <div class="weather-card">
            <h2><?= $weatherData['name'] ?>, <?= $weatherData['sys']['country'] ?></h2>
            
            <img class="icon" src="https://openweathermap.org/img/wn/<?= $weatherData['weather'][0]['icon'] ?>@2x.png" alt="Icone météo">
            
            <div class="temp"><?= round($weatherData['main']['temp']) ?>°C</div>
            <div class="desc"><?= $weatherData['weather'][0]['description'] ?></div>
            
            <div class="details">
                <div>
                    <span>💧 Humidité</span><br>
                    <strong><?= $weatherData['main']['humidity'] ?>%</strong>
                </div>
                <div>
                    <span>💨 Vent</span><br>
                    <strong><?= $weatherData['wind']['speed'] ?> m/s</strong>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>