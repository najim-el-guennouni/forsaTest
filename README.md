
# Symfony Project

## Requirements
Before you begin, ensure you have met the following requirements:
- **PHP**: Version 8.2 or higher
- **Composer**: [Download and install Composer](https://getcomposer.org/download/)
- **Node.js** and **npm/yarn**: Required for managing frontend assets
- **Symfony CLI** (Optional): [Install Symfony CLI](https://symfony.com/download) for better development experience

## Installation
Follow these steps to set up the project on your local machine.

### 1. Clone the Repository
Clone the project repository to your local machine using Git.

git clone https://github.com/najim-el-guennouni/forsaTest.git
cd forsaTest

### 2. Install Dependencies
Use Composer to install PHP dependencies.

composer install

# For npm
npm install

### 3. Set Up the Database
Create the database and run migrations.

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate


php bin/console doctrine:fixtures:load

### 5. Run the Symfony Server
You can start the Symfony development server using Symfony CLI or PHP's built-in server.

#### Using Symfony CLI:
symfony server:start

Open your browser and navigate to \`http://127.0.0.1:8000\` to see the application running.


## Acknowledgements
- [Symfony](https://symfony.com/)
- [Doctrine](https://www.doctrine-project.org/)
- [Twig](https://twig.symfony.com/)
