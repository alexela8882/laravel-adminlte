*03/07/19 08:37*

INSTRUCTION TO MIGRATE DATABASE FOR NEW WEBPORTAL APP FROM OLD TO NEW:
1. Create database webportal_[version]
2. Export backup sql files from old webportal db
3. Execute 'php artisan migrate' to migrate the newly added migration from updated webportal app
4. Execute 'php artisan db:seed' to seed the default roles & permissions
   Note: Make sure to comment out the existing roles & permissions in the seeder
5. Make folder 'migration_[version]'
6. Move a copy of migration with altered table schema to the folder; eg. themes table
7. Execute 'php artisan migrate --path=/database/migrations_[version]/'
   Note: Delete similar row in the old migration table if exists before execute
8. Execute 'php artisan cache:clear' to clear cache
9. Manually add permissions in the newly added roles
10. Test the webportal before deployment