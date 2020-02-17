# Welcome to Jarod-XP blog
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=JarodXP_blog-micro-framework&metric=security_rating)](https://sonarcloud.io/dashboard?id=JarodXP_blog-micro-framework) [![Maintainability](https://api.codeclimate.com/v1/badges/cffc3a45e6238af8ffa4/maintainability)](https://codeclimate.com/github/JarodXP/blog-micro-framework/maintainability)

This project aims to set the basis for a personal blog.

### Main criterias:
- Pure PHP (no framework)
- MVC structure
- Use of Twig as template engine
- External libraries through Composer
- Automated code review tool : Codeclimate (Maintainability) and Sonar Cloud (security) used.

### Project setup:
1. Clone repository
2. Create a database and import tables with the blog.sql script
3. Change the database info in the "config/db-config.yml" file with your own info
4. If php < 7.4:<br>
4.1 Install php 7.4 (for ubuntu: https://www.cloudbooklet.com/upgrade-php-version-to-php-7-4-on-ubuntu/)<br>
4.2 Install extensions (yaml, gd and mbstring are needed)
5. Create an .htaccess file or update your server config file with the following lines:

<blockquote><span style="font-style: italic">
#Redirects every request to the index.php except the public existing files (ie : images, styles and js)<br>
RewriteCond %{REQUEST_FILENAME} !-f<br>
RewriteRule ^(.*)$ /index.php [QSA,L]</span>
</blockquote>

6. On your host folder, do a composer install to get the dependencies.
7. At the first visit of the blog, you will be asked for a login and password.
You will be the first (and only) user registered as Admin.
For the moment, no other users are handled.
8. That's all folks! Enjoy


