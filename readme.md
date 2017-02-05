# YSFHQ Content Migrator

This application, developed with the Laravel PHP Framework (see below), is used to migrate content from YSFlight Headquarters' existing websites, notably our old [Drupal website](http://drupal.ysfhq.com) and [YSUpload.com](http://ysupload.com/) back to our more sustainable [phpBB forum](http://forum.ysfhq.com/). As the initial content is scattered across multiple database tables and columns, this tool is used to easily capture the important bits, then re-post the content in a templated fashion on the forum.

### Steps

1. Collect content from Drupal and YSUpload

  a. Drupal

    1. Addons
    2. Screenshots
    3. Stories (blog posts)
    4. Videos

  b. YSUpload

    1. Addons (metadata)
    2. Addons (downloadable files)

2. Save content (except for files on YSUpload) into a database, with schema set to match the output format (topic/post).
3. Repost on the forum. For each post...

  a. If `topic_id==null` then create a new topic with the data from the Post model.

  b. Otherwise, reply to the `topic_id` specified with the data from the Post model.

  c. Update all posts with their original authors and post times.

  d. (If YSUpload) copy the addon file from YSUpload to the attachment directory and add a record in phpBB's database, linking the attachment record to the already made post (with the original upload time).

### To Do

- YSUpload meta import should search for existing Drupal addon posts which use YSUpload
  if it finds it, then update post_id in YSUpload post model to point to Drupal post
- Implement file transfer process for YSUpload
- Add method to `PhpbbClient` which can add attachment record to database
- More work not yet realized

##### *Copyright (c) 2017 YSFlight Headquarters. All rights reserved.*

##### Developed by [Eric Tendian](https://github.com/EricTendian).

---

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
