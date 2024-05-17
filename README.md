# Permission Mock

## Introduction

Simple package with a simple premise, to mock permissions in a simple way for feature testing.

If you are wanting to develop a package for laravel that uses permissions and policies, 
you may want to test that your package works as expected. This package allows you to mock permissions in a simple way.

I found this useful when building a monolithic application using packages that had permissions and policies.

I wanted to keep my tests simple and not have to worry about setting up permissions and policies in my tests.
And I wanted to be able to define the permissions in my packages.

## Installation

You can install the package via composer:

```bash
composer require --dev ronappleton/spatie-laravel-permission-mock
```

## Usage

If using normal integer id fields for your models, you can use the following in your tests:

 - User
 - Role
 - Permission

If wanting to use uuids for your models, you can use the following in your tests:

 - UserUuid
 - RoleUuid
 - PermissionUuid

For Uuids you will need to set the following in your tests:

```php

use DatabaseMigrations;

protected function setUp(): void
{
    parent::setUp();

    config()->set('mock-permissions.uuids', true);

    $this->artisan('migrate:fresh', ['--database' => 'testing']);
}
```

You can then create permissions and roles in your tests, and assign them to users.

## Conclusion

You can now develop a package using permissions and policies and test them knowing your test will cover want you
want within the package and you can rely on your code working when your package is pulled into your application.


## Note

If you want to add whatever to this package simply PR it and ill add it in, please remember tests.

