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
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `php artisan db:seed`
6. `npm install`
7. `npm run build`

## Running
1. `php artisan serve`
   - Navigate to link given (default http://127.0.0.1:8000)
2. Login as `test@example.com` password `password`
3. Navigate to `Tasks`

## Notes
- I've included a package I've created `paasky/laravel-model-test`, that tests model relations with a single test
- A single User can only have 10 tasks at a time

## Potential next steps
- Task Update notification includes what exactly was updated (from -> to)
- Send Task Update notification to previous assignee if it's changed
- Update error handling to show a nice error if trying to create more than 10 tasks
- More comprehensive TaskAdmin tests (per column in table, table actions, update & delete)
