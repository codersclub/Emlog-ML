<p align="center">
  <img src="./admin/views/images/logo.png" width=100 />
</p>

<p align="center">
  <a href="./README.zh.md">中文</a> | English
</p>

# emlog

emlog is a lightweight blog and CMS website building system, dedicated to creating an easy-to-use personal cloud content management system.

## Function introduction

- Markdown editor: Built-in Markdown editor and automatic saving, making the creative process more comfortable and efficient.
- Multi-user roles: supports multiple user roles and provides user registration and login functions to facilitate the interaction between readers and authors.
- Multimedia Resource Manager: The built-in multimedia resource manager makes it easy to upload and manage various media resources such as pictures, audios, videos, and files.
- Template themes: The app store provides a variety of template themes to easily create a unique site.
- Plug-in ecology: It has a powerful plug-in extension system to quickly expand site functions to meet specific needs.
- Powerful SEO function: supports article URL customization, site and category page TDK customization, which helps improve the site's visibility in search engines.
- Customized sidebar management: Provides flexible sidebar component management.
- Custom pages: Supports the creation of custom pages, including message boards, personal introductions, etc., to help you create a more personalized and functional site.
- Tags and categories: Articles can be easily categorized and tagged, providing better information organization and retrieval capabilities.

## Environmental requirements

* PHP5.6, PHP7, PHP8, PHP7.4 recommended
* MySQL5.6 and above, 5.6 is recommended
* Recommended server environment: Linux + nginx
* Server panel software recommendation: Pagoda panel
* Recommended browsers: Chrome, Edge

## Installation Notes

1. Upload all decompressed files to the web root directory of the server or virtual host. You can also upload the zip package and decompress it online.
2. Access the pre-resolved domain name on the browser, and the program will automatically jump to the emlog installation page. Just follow the prompts to install it.
3. The installation process will not create a database. You need to create it in advance. Click to confirm the installation. The installation is successful.

## Docker

### Start via `docker run`

```bash
$ docker run --name emlog-pro -p 8080:80 -d emlog/emlog:pro-latest-php7.4-apache
```

### Start via `docker-compose`

1. cp config.sample.php config.php
2. docker network create emlog_network
3. docker-compose up
4. http://localhost:8080

## License Agreement

The license under which the Emlog software is released is the Free Software Foundation's GPLv3 (or higher): [LICENSE](/license.txt)
