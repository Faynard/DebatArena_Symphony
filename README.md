# DebateArena

## Technologies utilisées

- Symfony 6.4  
- PHP 8.1 ou supérieur  
- PostgreSQL  
- Doctrine ORM  
- EasyAdminBundle  
- Twig  
- AssetMapper

## Pour démarrer

### Prérequis

- PHP 8.1 ou supérieur  
- Composer  
- PostgreSQL

### Installation

1. **Cloner le dépôt :**

    ```bash
    git clone git@github.com:Faynard/DebatArena_Symphony.git
    cd DebateArena_Symphony
    ```

2. **Copier le fichier `.env` :**

    ```bash
    cp .env .env.local
    ```

3. **Configurer votre connexion à la base de données dans `.env.local`.**

4. **Installer les dépendances avec Composer :**

    ```bash
    composer install
    ```

5. **Créer la base de données :**

    ```bash
    php bin/console doctrine:database:create
    ```

6. **Lancer les migrations de base de données :**

    ```bash
    php bin/console doctrine:migration:migrate
    ```

    **Créer l’utilisateur "Anonyme" :**

    ```bash
    php bin/console app:create-anonymous-user
    ```

7. **(Optionnel) Charger les données de test (fixtures) :**

    ```bash
    php bin/console doctrine:fixtures:load
    ```

8. **Démarrer le serveur Symfony :**

    ```bash
    symfony server:start
    ```

    Ou avec le serveur PHP intégré :

    ```bash
    php -S localhost:8000 -t public
    ```

9. **Accéder à l'application dans votre navigateur à l'adresse `http://localhost:8000`.**
