# On utilise une image officielle PHP avec Apache
FROM php:8.2-apache

# On active le module de réécriture d'Apache (bonnes pratiques)
RUN a2enmod rewrite

# On copie tes fichiers (index.php) dans le dossier du serveur web de l'image
COPY . /var/www/html/

# On dit à Render que le port 80 sera utilisé
EXPOSE 80