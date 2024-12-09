## Creating A Demo App

If you want to just try out the features of this package you can get started with the following.

The examples on this page are primarily added for assistance in creating a quick demo app for troubleshooting purposes, to post the repo on github for convenient sharing to collaborate or get support.

If you're new to Laravel or to any of the concepts mentioned here, you can learn more in the [Laravel documentation](https://laravel.com/docs/) and in the free videos at Laracasts such as with the [Laravel 11 in 30 days](https://laracasts.com/series/30-days-to-learn-laravel-11) or [Laravel 8 From Scratch](https://laracasts.com/series/laravel-8-from-scratch/) series.

### Initial setup:

```sh
cd ~/Sites
laravel new mypermissionsdemo
# (Choose Laravel Breeze, choose Blade with Alpine)
# (choose your own dark-mode-support choice)
# (choose your desired testing framework)
# (say Yes to initialize a Git repo, so that you can track your code changes)
# (Choose SQLite)

cd mypermissionsdemo

# the following git commands are not needed if you Initialized a git repo while "laravel new" was running above:
git init
git add .
git commit -m "Fresh Laravel Install"

# These Environment steps are not needed if you already selected SQLite while "laravel new" was running above:
cp -n .env.example .env
sed -i '' 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i '' 's/DB_DATABASE=/#DB_DATABASE=/' .env
touch database/database.sqlite

# Package
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
git add .
git commit -m "Add Spatie Laravel Permissions package"
php artisan migrate:fresh

# Add `HasRoles` trait to User model
sed -i '' $'s/use HasFactory, Notifiable;/use HasFactory, Notifiable;\\\n    use \\\\Spatie\\\\Permission\\\\Traits\\\\HasRoles;/' app/Models/User.php
sed -i '' $'s/use HasApiTokens, HasFactory, Notifiable;/use HasApiTokens, HasFactory, Notifiable;\\\n    use \\\\Spatie\\\\Permission\\\\Traits\\\\HasRoles;/' app/Models/User.php
git add . && git commit -m "Add HasRoles trait"
```

If you didn't install Laravel Breeze or Jetstream, add Laravel's basic auth scaffolding:
```php
composer require laravel/ui --dev
php artisan ui bootstrap --auth
# npm install && npm run prod
git add . && git commit -m "Setup auth scaffold"
```

When enabled, teams permissions offers you flexible control for a variety of scenarios. The idea behind teams permissions is inspired by the default permission implementation of [Laratrust](https://laratrust.santigarcor.me/).


### Install from git
```shell
git clone https://github.com/yuanhuisun/meta-board.git
cp -r .env.example .env
touch database/database.sqlite 
composer install
php artisan migrate:fresh
php artisan key:generate
pnpm install
pnpm dev run
```


## Enabling Teams Permissions Feature

NOTE: These configuration changes must be made **before** performing the migration when first installing the package.

If you have already run the migration and want to upgrade your implementation, you can run the artisan console command `php artisan permission:setup-teams`, to create a new migration file named [xxxx_xx_xx_xx_add_teams_fields.php](https://github.com/spatie/laravel-permission/blob/main/database/migrations/add_teams_fields.php.stub) and then run `php artisan migrate` to upgrade your database tables.

Teams permissions can be enabled in the permission config file:

```php
// config/permission.php
'teams' => true,
```

Also, if you want to use a custom foreign key for teams you set it in the permission config file:
```php
// config/permission.php
'team_foreign_key' => 'custom_team_id',
```

## Working with Teams Permissions

After implementing a solution for selecting a team on the authentication process 
(for example, setting the `team_id` of the currently selected team on the **session**: `session(['team_id' => $team->team_id]);` ), 
we can set global `team_id` from anywhere, but works better if you create a `Middleware`. 

Example Team Middleware:

```php
namespace App\Http\Middleware;

class TeamsPermission
{
    
    public function handle($request, \Closure $next){
        if(!empty(auth()->user())){
            // session value set on login
            setPermissionsTeamId(session('team_id'));
        }
        // other custom ways to get team_id
        /*if(!empty(auth('api')->user())){
            // `getTeamIdFromToken()` example of custom method for getting the set team_id 
            setPermissionsTeamId(auth('api')->user()->getTeamIdFromToken());
        }*/
        
        return $next($request);
    }
}
```

**YOU MUST ALSO** set [the `$middlewarePriority` array](https://laravel.com/docs/master/middleware#sorting-middleware) in `app/Http/Kernel.php` to include your custom middleware before the `SubstituteBindings` middleware, else you may get *404 Not Found* responses when a *403 Not Authorized* response might be expected.

For example, in Laravel 11.27+ you can add something similiar to the `boot` method of your `AppServiceProvider`.

```php
use App\Http\Middleware\YourCustomMiddlewareClass;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /** @var Kernel $kernel */
        $kernel = app()->make(Kernel::class);

        $kernel->addToMiddlewarePriorityBefore(
            SubstituteBindings::class,
            YourCustomMiddlewareClass::class,
        );
    }
}
```

## Roles Creating

When creating a role you can pass the `team_id` as an optional parameter
 
```php
// with null team_id it creates a global role; global roles can be assigned to any team and they are unique
Role::create(['name' => 'writer', 'team_id' => null]);

// creates a role with team_id = 1; team roles can have the same name on different teams
Role::create(['name' => 'reader', 'team_id' => 1]);

// creating a role without team_id makes the role take the default global team_id
Role::create(['name' => 'reviewer']);
```

## Roles/Permissions Assignment & Removal

The role/permission assignment and removal for teams are the same as without teams, but they take the global `team_id` which is set on login.

## Changing The Active Team ID

While your middleware will set a user's `team_id` upon login, you may later need to set it to another team for various reasons. The two most common reasons are these:

### Switching Teams After Login
If your application allows the user to switch between various teams which they belong to, you can activate the roles/permissions for that team by calling `setPermissionsTeamId($new_team_id)` and unsetting relations as described below.

### Administrating Team Details
You may have created a User-Manager page where you can view the roles/permissions of users on certain teams. For managing that user in each team they belong to, you must also use `setPermissionsTeamId($new_team_id)` to cause lookups to relate to that new team, and unset prior relations as described below.

### Querying Roles/Permissions for Other Teams
Whenever you switch the active `team_id` using `setPermissionsTeamId()`, you need to `unset` the user's/model's `roles` and `permissions` relations before querying what roles/permissions that user has (`$user->roles`, etc) and before calling any authorization functions (`can()`, `hasPermissionTo()`, `hasRole()`, etc).

Example:
```php
// set active global team_id
setPermissionsTeamId($new_team_id);

// $user = Auth::user();

// unset cached model relations so new team relations will get reloaded
$user->unsetRelation('roles')->unsetRelation('permissions');

// Now you can check:
$roles = $user->roles;
$hasRole = $user->hasRole('my_role');
$user->hasPermissionTo('foo');
$user->can('bar');
// etc
```

## Defining a Super-Admin on Teams

Global roles can be assigned to different teams, and `team_id` (which is the primary key of the relationships) is always required. 

If you want a "Super Admin" global role for a user, when you create a new team you must assign it to your user. Example:

```php
namespace App\Models;

class YourTeamModel extends \Illuminate\Database\Eloquent\Model
{
    // ...
    public static function boot()
    {
        parent::boot();

        // here assign this team to a global user with global default role
        self::created(function ($model) {
           // temporary: get session team_id for restore at end
           $session_team_id = getPermissionsTeamId();
           // set actual new team_id to package instance
           setPermissionsTeamId($model);
           // get the admin user and assign roles/permissions on new team model
           User::find('your_user_id')->assignRole('Super Admin');
           // restore session team_id to package instance using temporary value stored above
           setPermissionsTeamId($session_team_id);
        });
    }
    // ...
}
```



### Add some basic permissions
- Add a new file, `/database/seeders/PermissionsDemoSeeder.php` such as the following (You could create it with `php artisan make:seed` and then edit the file accordingly):

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionUserSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions - CRUD : Create / Read / Update / Delete , by Yuanhui at 2024/12/06
        // task / ticket level
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'read tasks']);
        Permission::create(['name' => 'update tasks']);
        Permission::create(['name' => 'delete tasks']);
        // workflow level
        Permission::create(['name' => 'create workflows']);
        Permission::create(['name' => 'read workflows']);
        Permission::create(['name' => 'update workflows']);
        Permission::create(['name' => 'execute workflows']);
        Permission::create(['name' => 'delete workflows']);
        Permission::create(['name' => 'publish workflows']);
        Permission::create(['name' => 'unpublish workflows']);
        

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'user']);
        $role1->givePermissionTo('create tasks');
        $role1->givePermissionTo('read tasks');
        $role1->givePermissionTo('update tasks');
        $role1->givePermissionTo('delete tasks');
        $role1->givePermissionTo('execute workflows');

        $role2 = Role::create(['name' => 'app-admin']);
        $role2->givePermissionTo('create workflows');
        $role2->givePermissionTo('read workflows');
        $role2->givePermissionTo('update workflows');
        $role2->givePermissionTo('execute workflows');
        $role2->givePermissionTo('delete workflows');
        $role2->givePermissionTo('publish workflows');
        $role2->givePermissionTo('unpublish workflows');

        $role3 = Role::create(['name' => 'sys-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example App Admin User',
            'email' => 'appadmin@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Example Sys-Admin User',
            'email' => 'sysadmin@example.com',
        ]);
        $user->assignRole($role3);
    }
}

```


### Application Code
The permissions created in the seeder above imply that there will be some sort of Posts or Article features, and that various users will have various access control levels to manage/view those objects.

Your app will have Models, Controllers, routes, Views, Factories, Policies, Tests, middleware, and maybe additional Seeders.

You can see examples of these in the demo app at https://github.com/drbyte/spatie-permissions-demo/


### Quick Examples
If you are creating a demo app for reporting a bug or getting help with troubleshooting something, skip this section and proceed to "Sharing" below.

If this is your first app with this package, you may want some quick permission examples to see it in action. If you've set up your app using the instructions above, the following examples will work in conjunction with the users and permissions created in the seeder.

Three users were created: test@example.com, admin@example.com, superadmin@example.com and the password for each is "password".

`/resources/views/dashboard.php`
```diff
    <div class="p-6 text-gray-900">
        {{ __("You're logged in!") }}
    </div>
+    @can('edit articles')
+    You can EDIT ARTICLES.
+    @endcan
+    @can('publish articles')
+    You can PUBLISH ARTICLES.
+    @endcan
+    @can('only super-admins can see this section')
+    Congratulations, you are a super-admin!
+    @endcan
```
With the above code, when you login with each respective user, you will see different messages based on that access.

Here's a routes example with Breeze and Laravel 11. 
Edit `/routes/web.php`:
```diff
-Route::middleware('auth')->group(function () {
+Route::middleware('role_or_permission:publish articles')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
```
With the above change, you will be unable to access the user "Profile" page unless you are logged in with "admin" or "super-admin". You could change `role_or_permission:publish_articles` to `role:writer` to make it only available to the "test" user.

## Sharing
To share your app on Github for easy collaboration:

- create a new public repository on Github, without any extras like readme/etc.
- follow github's sample code for linking your local repo and uploading the code. It will look like this:

```sh
git remote add origin git@github.com:YOURUSERNAME/REPONAME.git
git push -u origin main
```
The above only needs to be done once. 

- then add the rest of your code by making new commits:

```sh
git add .
git commit -m "Explain what your commit is about here"
git push origin main
```
Repeat the above process whenever you change code that you want to share.

Those are the basics!




## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
