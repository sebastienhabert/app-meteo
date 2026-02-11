# Application Météo

Application web composée d'une API Symfony et d'un frontend Vue.js, orchestrée avec Docker.

## Architecture

- **Frontend** : Vue.js 3 (Vite)
- **API** : Symfony 7 - PHP 8.4-FPM
- **Serveur Web** : Caddy
- **Base de données** : SQLite

---

## Installation sur un nouveau poste

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd app-meteo
```

### 2. Configuration des variables d'environnement

Par défaut, Symfony utilise le fichier `api/.env`. Si vous devez surcharger des configurations (comme l'URL de la base de données), créez un fichier `.env.local` dans le dossier `api/`.

### 3. Lancer le projet avec Docker

Le projet est configuré pour installer automatiquement les dépendances (Composer pour l'API et NPM pour le Front) au premier lancement.

```bash
docker-compose up -d --build
```

Cette commande va :
- Construire les images Docker.
- Installer les packages PHP via `composer install` (si configuré dans le point d'entrée).
- Installer les packages Node via `npm install` dans le conteneur front.
- Lancer le serveur de développement Vite.
- Lancer le serveur Caddy.

### 4. Accès à l'application

- **Frontend** : [http://localhost](http://localhost) (via Caddy)
- **API (Entrypoint)** : [http://localhost/api](http://localhost/api) (selon config Caddy)
