Youtube service.
============

***The following technology are using: symfony4 for backend and bootstrap4/jquery for frontend.***

### Execute the following commands:
- `composer install`
- `yarn install`
- `yarn run encore dev` 

***Modify .env file and paste your data.***

### Create temp data:
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:schema:create`
- `php bin/console doctrine:query:sql "INSERT INTO playlist (youtube_id) VALUES ('REPLACE_ME')"`
- `php bin/console video:update`

***Access to admin path: /admin. User: admin/password.***