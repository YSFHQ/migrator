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

- Add posttype column to schema
  use forum_id and topic_id to generate for existing posts
- YSUpload meta import should search for existing Drupal addon posts which use YSUpload
  if it finds it, then update post_id in YSUpload post model to point to Drupal post
- Implement file transfer process for YSUpload
- Add method to `PhpbbClient` which can add attachment record to database
- More work not yet realized

##### *Copyright (c) 2015 YSFlight Headquarters. All rights reserved.*

##### Developed by [Eric Tendian](https://github.com/EricTendian).

---

## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/downloads.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, and caching.

Laravel aims to make the development process a pleasing one for the developer without sacrificing application functionality. Happy developers make the best code. To this end, we've attempted to combine the very best of what we have seen in other web frameworks, including frameworks implemented in other languages, such as Ruby on Rails, ASP.NET MVC, and Sinatra.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing To Laravel

**All issues and pull requests should be filed on the [laravel/framework](http://github.com/laravel/framework) repository.**

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
