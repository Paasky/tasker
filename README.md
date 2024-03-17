# Tasker

## Requirements
- PHP 8.2 or higher
- Composer 2.6 or higher
- Node 18 or higher
- NPM 9.8 or higher
- `extension=intl` enabled in php.ini
  - _Use `php --ini` to find location of your ini file_

## Installation
1. `composer install`
2. `php artisan migrate`
3. `php artisan db:seed`
4. `npm install`
5. `npm run build`
6. Edit `.env`
   - Change `APP_NAME=Tasker`

## Running
1. `php artisan serve`
   - Navigate to link given (default http://127.0.0.1:8000)
2. Login as `test@example.com` password `password`

## Notes
- I've included a package I've created `paasky/laravel-model-test`, that tests model relations with a single test
