# Welcome to Jarod-XP blog
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=JarodXP_blog-micro-framework&metric=security_rating)](https://sonarcloud.io/dashboard?id=JarodXP_blog-micro-framework) [![Maintainability](https://api.codeclimate.com/v1/badges/cffc3a45e6238af8ffa4/maintainability)](https://codeclimate.com/github/JarodXP/blog-micro-framework/maintainability)

This project aims to set the basis for a personal blog.

<strong>Main criterias:</strong>
- Pure PHP (no framework)
- MVC structure
- Use of Twig as template engine
- External libraries through Composer
- Automated code review tool : Codeclimate (Maintainability) and Sonar Cloud (security) used.

<strong>Project setup:</strong>
1. Download files
2. On your folder, do a composer install to get the dependencies.
3. Create database and set database connection information in src/config/blog-config.yml<br>
A data set is available for testing : dataSet.sql.
You can upload your file directly on your database.
4. Change server temp directory in the config file.
