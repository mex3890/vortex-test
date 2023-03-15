<br><br>
<div align="center">
    <img height="200" src="public/img/vortex.png" alt="Vortex">
</div>

<div align="center">
    <a href="https://github.com/mex3890/vortex/"><img height="120" src="public/img/vortex_name.png" alt="Vortex"></a>
</div>

<p align="center">
<a href="https://github.com/mex3890/vortex/"><img src="https://badgen.net/badge/Support/mail/purple" alt=""></a>
<a href="https://github.com/mex3890/vortex/"><img src="https://badgen.net/badge/icon/discord/black?icon=discord&label" alt=""></a><br>
<a href="https://github.com/mex3890/vortex/"><img src="https://badgen.net/badge/Lts/1.0.3/cyan" alt=""></a>
<a href="https://getcomposer.org/"><img src="https://badgen.net/badge/Composer/v2.4/orange" alt=""></a>
<a href="https://www.php.net/"><img src="https://badgen.net/badge/icon/v8.1?icon=php&label" alt=""></a>
<a href="https://github.com/mex3890/vortex/pulse/monthly"><img src="https://badgen.net/badge/icon/insights/green?icon=github&label" alt=""></a>
<a href="https://github.com/mex3890/vortex/"><img src="https://badgen.net/badge/icon/v8.19/red?icon=npm&label" alt=""></a>
</p>
<hr>

# Summary

- `1 Installation`
- `2 Cosmo Commands`
   - `2.1 Vortex`
     - `2.1.1 vortex:install` 
     - `2.1.2 vortex:server` 
   - `2.2 Migration`
     - `2.2.1 migrate`
     - `2.2.2 migrate:rollback`
     - `2.2.3 migrate:list`
   - `2.3 Seed`
   - `2.4 Make`
     - `2.4.1 make:migration`
     - `2.4.2 make:factory`
     - `2.4.3 make:seed`
     - `2.4.4 make:controller`
     - `2.4.5 make:middleware`
     - `2.4.6 make:model`
     - `2.4.7 make:service`
     - `2.4.8 make:exception`
- `3 Database`
   - `3.1 Migration`
     - `3.1.1 Up method`
     - `3.1.2 Table structure`
     - `3.1.3 Down method`
     - `3.1.4 Column types`
     - `3.1.5 Column constraints`
   - `3.2 Factory`
   - `3.3 Seed`
- `4 Model`
  - `4.1 Query builder`
- `5 Controller`
   - `5.1 Validation`

<hr>

## /|\ (1) Installation

- On terminal run the composer command with your new project name

```shell
composer create-project vortex-framework/vortex YOUR_PROJECT_NAME
```

- Copy the .env.example and rename to .env
- **Set the variables** to continue the installation

```dotenv
APP_NAME=$PROJECT_NAME
APP_ENV=local
APP_URL=$PROJECT_URL

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DATABASE_NAME
DB_USERNAME=$DATABASE_USERNAME
DB_PASSWORD=$DATABASE_PASSWORD
DB_CHARSET=utf8mb4
```

- On terminal go to your root project directory and run Cosmo Vortex Installation command

```shell
php cosmo vortex:install
```

- Your output should be equal to this
- [x] load environment........... SUCCESS<br>
- [x] first migrations............... SUCCESS<br>
- [x] set time zone................. SUCCESS<br>
- [x] composer install................. SUCCESS<br>
- [x] npm install................. SUCCESS<br>
- [x] npm compile................. SUCCESS<br>


- To test your project run:

```shell
php cosmo migrate
php cosmo seed
```

- That create a User default table and create 20 Users

## /|\ (2) Cosmo Commands

- Cosmo is a command line interface that help you in your project development

- Run Cosmo index command, that list for you all available commands

```shell
php cosmo
```

### (2.1) Vortex

`(2.1.1) vortex:install`
- This command install all dependencies and set up your basic functionality

````shell
php cosmo vortex:install
````

`(2.1.2) vortex:serve`
- This command up a PHP server, you can set the port in `APP_LOCALHOST_SERVER_PORT` on .env

````shell
php cosmo vortex:install
````

### (2.2) Migration
- For understand more about `migrations` look the Migrations explanation (3.1)

`(2.2.1) migrate`
- This command run all `Up` methods from `migrations` in App/Migrations directory
- You can pass the filename of the migration to run else all not ran migrations will run

````shell
php cosmo migrate
````

`(2.2.2) migrate:rollback`
- This command run all `Down` methods from `migrations` in App/Migrations directory
- You can pass the `filename` of the migration to run else all not ran migrations will run

````shell
php cosmo migrate:rollback
````

`(2.2.2) migrate:list`
- This command display all `migrations` and your status like `RUN` or `RAN`
- You can pass the `filename` of the migration to run else all not ran migrations will run

````shell
php cosmo migrate:rollback
````

### (2.3) Seed
- This command run all `handle` methods from `seeds` in App/Seeds directory
- You can pass the `filename` of the seed to run else all not ran migrations will run

````shell
php cosmo seed
````

### (2.4) Make

`(2.4.1) make:migration`
- This command create a new Migration class in App/Migrations directory
- You need pass the classname

````shell
php cosmo make:migration $MigrationName
````

`(2.4.2) make:factory`
- This command create a new Factory class in App/Factories directory
- You need pass the classname

````shell
php cosmo make:factory $FactoryName
````

`(2.4.3) make:seed`
- This command create a new Seed class in App/Seeds directory
- You need pass the classname

````shell
php cosmo make:seed $SeedName
````

`(2.4.4) make:controller`
- This command create a new Controller class in App/Controllers directory
- You need pass the classname

````shell
php cosmo make:controller $ControllerName
````

`(2.4.5) make:middleware`
- This command create a new Seed class in App/Seeds directory
- You need pass the classname

````shell
php cosmo make:seed $SeedName
````

`(2.4.6) make:model`
- This command create a new Controller class in App/Controllers directory
- You need pass the classname

````shell
php cosmo make:controller $ControllerName
````

`(2.4.7) make:service`
- This command create a new Service class in App/Services directory
- You need pass the classname

````shell
php cosmo make:service $ServiceName
````

`(2.4.8) make:exception`
- This command create a new Exception class in App/Exceptions directory
- You need pass the classname

````shell
php cosmo make:exception $ExceptionName
````

## /|\ (3) Database

### (3.1) Migration

`(3.1.1) Up method`
- The Up method will be called when you run the migrate command (2.2.1)

`(3.1.2) Table structure`
- This is a simple example of Users table
- The Schema:create need the table name and anonymous function with the table columns like:
```php
    public static function up(): void
    {
        Schema::create('users', function (TableBuilder $table) {
            $table->id()->unique()->notNull()->autoIncrement();
            $table->varchar('name', '255')->notNull();
            $table->varchar('email', 255)->unique()->notNull();
            $table->varchar('password', 255)->notNull();
            $table->timeStamp('created_at');
            $table->timeStamp('updated_at');
            return $table;
        });
    }
```

`(3.1.3) Down method`
- The Down method will be called when you run migrate:rollback command (2.2.2)
```php
    public static function down(): void
    {
        Schema::drop('users');
    }
```

`(3.1.4) Column types`

|          | COLUMN TYPES |            | 
|:--------:|:------------:|:----------:|
| BIG INT  |     ENUM     | SMALL INT  |       
|  BINARY  |    FLOAT     |    TEXT    |                       
|   BIT    |     INT      |    TIME    |                      
|   BLOB   |   INTEGER    | TIMESTAMP  |                     
|   BOOL   |     JSON     | TINY BLOB  |                    
| BOOLEAN  |  LONG BLOB   |  TINY INT  |                   
|   CHAR   |  LONG TEXT   | TINY TEXT  |                  
|   DATE   | MEDIUM BLOB  |    UUID    |                 
| DATETIME |  MEDIUM INT  | VAR BINARY |                 
| DECIMAL  | MEDIUM TEXT  |  VARCHAR   |                 
|  DOUBLE  |     SET      |    YEAR    |                 

`(BIG INT)`
- **Description** - `Big Int`
- **Parameters** - string `$column_name`
- **Usage** 
```php
$table->bigInt($column_name);
``` 

`(BINARY)`
- **Description** - `Binary`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->binary($column_name);
``` 

`(BIT)`
- **Description** - `Bit`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->bit($column_name);
``` 

`(BLOB)`
- **Description** - `Blob`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->blob($column_name);
``` 

`(BOOL)`
- **Description** - `Bool`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->bool($column_name);
``` 

`(BOOLEAN)`
- **Description** - `Boolean`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->boolean($column_name);
``` 

`(CHAR)`
- **Description** - `Char`
- **Parameters** - string `$column_name`, int `$var_length`
- **Usage**
```php
$table->char($column_name, $var_length);
``` 

`(DATE)`
- **Description** - `Date`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->date($column_name);
``` 

`(DATE TIME)`
- **Description** - `Date Time`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->dateTime($column_name);
``` 

`(DECIMAL)`
- **Description** - `Decimal`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->decimal($column_name);
``` 

`(DOUBLE)`
- **Description** - `Double`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->double($column_name);
``` 

`(ENUM)`
- **Description** - `Enum`
- **Parameters** - string `$column_name` and array `$options`
- **Usage**
```php
$table->enum($column_name, $options);
``` 

`(FLOAT)`
- **Description** - `Float`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->float($column_name);
``` 

`(ID)`
- **Description** - A `Big Int` column
- **Parameters** - string `$column_name` with default value 'id'
- **Usage**
```php
$table->id($column_name);
``` 

`(INT)`
- **Description** - `Int`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->int($column_name);
``` 

`(INTEGER)`
- **Description** - `Integer`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->integer($column_name);
``` 

`(JSON)`
- **Description** - `Json`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->json($column_name);
``` 

`(LONG BLOB)`
- **Description** - `Long Blob`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->longBlob($column_name);
``` 

`(LONG TEXT)`
- **Description** - `Long Text`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->longText($column_name);
``` 

`(MEDIUM BLOB)`
- **Description** - `Medium Blob`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->mediumBlob($column_name);
``` 

`(MEDIUM INT)`
- **Description** - `Medium Int`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->mediumInt($column_name);
``` 

`(MEDIUM TEXT)`
- **Description** - `Medium Text`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->mediumText($column_name);
``` 

`(SET)`
- **Description** - `Set`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->set($column_name);
``` 

`(SMALL INT)`
- **Description** - `Small Int`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->smallInt($column_name);
``` 

`(TEXT)`
- **Description** - `Text`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->text($column_name);
``` 

`(TIME)`
- **Description** - `Time`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->time($column_name);
``` 

`(TIMESTAMP)`
- **Description** - `Timestamp`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->timestamp($column_name);
``` 

`(TINY BLOB)`
- **Description** - `Tiny Blob`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->tinyBlob($column_name);
```

`(TINY INT)`
- **Description** - `Tiny Int`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->tinyInt($column_name);
```

`(TINY TEXT)`
- **Description** - `Tiny Text`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->tinyText($column_name);
```

`(UUID)`
- **Description** - A `Varchar` column
- **Parameters** - string `$column_name` with default value 'id'
- **Usage**
```php
$table->uuid($column_name);
```

`(VAR BINARY)`
- **Description** - `Var Binary`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->varBinary($column_name);
``` 

`(VARCHAR)`
- **Description** - `Varchar`
- **Parameters** - string `$column_name`, int `$varchar_length`
- **Usage**
```php
$table->varchar($column_name, $varchar_length);
```

`(YEAR)`
- **Description** - `Year`
- **Parameters** - string `$column_name`
- **Usage**
```php
$table->year($column_name);
```

`(3.1.5) Column constraints`

|                | COLUMN CONSTRAINTS |             | 
|:--------------:|:------------------:|:-----------:|
| AUTO INCREMENT |    FOREIGN KEY     | PRIMARY KEY |       
|    DEFAULT     |      NOT NULL      |   UNIQUE    |                       

`(AUTO INCREMENT)`
- **Description** - `Auto_increment`
- **Usage**
```php
$table->id()->autoIncrement();
```

`(DEFAULT)`
- **Description** - `Default`
- **Usage**
```php
$table->varchar('name', 100)->default('default_value');
```

`(FOREIGN KEY)`
- **Description** - `Foreign Key`
- **Usage**
```php
$table->int('user_id')->forignKey('users', 'id');
```

`(NOT NULL)`
- **Description** - `Not null`
- **Usage**
```php
$table->id()->notNull();
```

`(PRIMARY KEY)`
- **Description** - `Primary key`
- **Usage**
```php
$table->id()->primaryKey();
```

`(UNIQUE)`
- **Description** - `Unique`
- **Usage**
```php
$table->varchar('email', 100)->unique();
```

### (3.2) Factory
- The factory classes are used to mount new Model's object to insert in the database, you can use the faker to help you

- **Usage**
````php
    class UserFactory extends Factory
    {
        public static function frame(): array
        {
            return [
                'name' => faker()->name(),
                'email' => faker()->safeEmail,
                'password' => Hash::hashPassword('password'),
                'created_at' => DateTime::currentDateTime(),
            ];
        }
    }
````

### (3.3) Seed
- Use seeds for massive insert into your database. 
In additional you can use your Factory class to personalize your inserts.
You need write your code into method `handler` in your Seed class.<br><br>
- Run seeds with the command:
````shell
php cosmo seed
````

`This method create only one User`
````php
    class UserSeeder extends Seeder
{
    public static function handler(): void
    {
        Schema::insert('users', [
            'name' => 'Username',
            'email' => 'username@vortex.com',
            'password' => Hash::hashPassword('password'),
            'created_at' => DateTime::currentDateTime(),
        ])->get();
    }
}
````

`This method enable you to set the number of inserts`
````php
    class UserSeeder extends Seeder
{
    public static function handler(): void
    {   
        self::factory('users', UserFactory::class, 20);
    }
}
````

## /|\ (4) Model
- The Model represent the abstraction of the object who you need represent.
First step is set the `$table` attribute
````php
class User extends Model
{
    public string $table = 'users';
}
````

- From Model class you can call some methods to interact with your database,
this is the list of available methods using User class for example:

#### CREATE

````php
$user = new User([
    'name' => $username,
    'email' => $email,
    'password' => $password
]);

$user->create();
````

#### DELETE

````php
User::delete()->where('id', $user_id)->get();
````

#### FIND

````php
$user->find(['id', 'email'])->where('name', 'A%', 'like')->get();
````

#### FIRST

````php
$user->first();
````

#### LAST

````php
$user->last();
````

#### UPDATE

````php
User::update([
    'name' => 'new_username',
    'email' => 'new_email'
])->where('id', $user_id)->get();
````

### (4.1) Query builder

[//]: # ()
[//]: # (## Validations)

[//]: # ()
[//]: # (`The validation Class`)

[//]: # ()
[//]: # (You can use the Validation class to validate your requests, to choose the validations of each field you must pass an)

[//]: # (array containing the respective rules as in the example below)

[//]: # ()
[//]: # (```php)

[//]: # (Validation::check&#40;$attributes, $rules, $feedback&#41;;)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER    | TYPE  | DESCRIPTION                                            | DEFAULT |)

[//]: # (|--------------|:-----:|--------------------------------------------------------|:-------:|)

[//]: # (| $attributes  | array | The array with the data to be validated                |    X    |)

[//]: # (| $rules       | array | The array containing the rules to be used to validated |    X    |)

[//]: # (| $feedback    | array | An array with the responses for invalid cases          |    X    |)

[//]: # ()
[//]: # (One example of an application of the validation method)

[//]: # ()
[//]: # (````php)

[//]: # ($rules = [)

[//]: # (    'email' => [)

[//]: # (        'required',)

[//]: # (        'email')

[//]: # (    ],)

[//]: # (    'password' => [)

[//]: # (        'required',)

[//]: # (        ['string', 10, 50],)

[//]: # (        ['password', ['upper-case', 'special-character']])

[//]: # (];)

[//]: # ()
[//]: # ($feedback = [)

[//]: # (     'email' => [)

[//]: # (        'required' => 'The email is required',)

[//]: # (        'email' => 'Enter with a valid email')

[//]: # (     ],)

[//]: # (     'password' => [)

[//]: # (        'required' => 'The password is required',)

[//]: # (        'string' => 'The password must be a string',)

[//]: # (        'string.min' => 'Minimum characters is 10',)

[//]: # (        'string.max' => 'Maximum characters is 50',)

[//]: # (        'password.upper-case' => 'Need upper case character',)

[//]: # (        'password.special-character' => 'Need special character')

[//]: # (     ])

[//]: # (])

[//]: # ()
[//]: # (public static function login&#40;Request $request&#41;: void)

[//]: # (    {)

[//]: # (        Validation::check&#40;$request->attributes&#40;&#41;, self::$rules, self::$feedback&#41;;)

[//]: # (        $attributes = $request->attributes&#40;&#41;;)

[//]: # ()
[//]: # (        $verifyServer = new VerifyUser&#40;$attributes['email'], $attributes['password']&#41;;)

[//]: # (        $verify_password = $verifyServer->verifyUserByEmail&#40;&#41;;)

[//]: # ()
[//]: # (        if &#40;$verifyServer->verifyUserByEmail&#40;&#41;&#41; {)

[//]: # (            redirect&#40;'/home'&#41;;)

[//]: # (        })

[//]: # ()
[//]: # (        redirect&#40;'/'&#41;;)

[//]: # (    })

[//]: # (````)

[//]: # ()
[//]: # (### List of available validation rules)

[//]: # ()
[//]: # (`bool`)

[//]: # (- **Description** - Simple validation to **bool** attributes)

[//]: # (- **Usage** - `'bool'` )

[//]: # (- **Parameters** - **Don't** use parameters)

[//]: # ()
[//]: # (`boolean`)

[//]: # (- **Description** - Simple validation to **boolean** attributes)

[//]: # (- **Usage** - `'boolean'`)

[//]: # (- **Parameters** - **Don't** use parameters)

[//]: # ()
[//]: # (`date`)

[//]: # (- **Description** - An validation for **date** attributes)

[//]: # (- **Usage** - `'date'`, `['date', 'Y-m-d']`)

[//]: # (- **Parameters** - `format`, you can set the format for validation, the default format is set in `.env` like `DATE_FORMAT=Y-m-d`)

[//]: # ()
[//]: # (`date_time`)

[//]: # (- **Description** - Simple validation for **date-time**)

[//]: # (- **Usage** - `'bool'`)

[//]: # (- **Parameters** - `format`, you can set the format for validation, )

[//]: # (the default format is set in `.env` from merge `DATE_FORMAT=Y-m-d` and `TIME_FORMAT=H:i:s`)

[//]: # ()
[//]: # (`email`)

[//]: # (- **Description** - Simple validation for **email** attributes)

[//]: # (- **Usage** - `'email'`)

[//]: # (- **Parameters** - **Don't** use parameters)

[//]: # ()
[//]: # (`file`)

[//]: # (- **Description** - Complete validation for **file** attributes)

[//]: # (- **Usage** - `'file'`, `['file', ['pdf', 'php', 'html']]`, `['file', ['pdf', 'php', 'html'], 1000]`)

[//]: # (- **Parameters** - `extensions`, `max size limit`, all parameters are optional)

[//]: # ()
[//]: # (`float`)

[//]: # (- **Description** - Simple validation for **float** attributes)

[//]: # (- **Usage** - `'float'`, `['float', -100]`, `['float', 10, 100.7]`)

[//]: # (- **Parameters** - `min`, `max`, all parameters are optional)

[//]: # ()
[//]: # (`image`)

[//]: # (- **Description** - Complete validation for **image** attributes)

[//]: # (- **Usage** - `'image'`, `['image', ['png', 'jpeg']]`, `['image', ['png'], 1000]`)

[//]: # (- **Parameters** - `extensions`, `max size limit`, all parameters are optional)

[//]: # ()
[//]: # (`int`)

[//]: # (- **Description** - Simple validation for **int** attributes)

[//]: # (- **Usage** - `'int'`, `['int', -100]`, `['int', 10, 1000]`)

[//]: # (- **Parameters** - `min`, `max`, all parameters are optional)

[//]: # ()
[//]: # (`password`)

[//]: # (- **Description** - Complete validation for **password** attributes)

[//]: # (- **Usage** - `['password', ['number', 'upper-case', 'lower-case', 'special-character']]`)

[//]: # (- **Parameters** - **array filters**, you can set the required constraints in this list)

[//]: # (- `number` `upper-case` `lower-case` `special-character`)

[//]: # ()
[//]: # (`required`)

[//]: # (- **Description** - Simple validation for **required** attributes)

[//]: # (- **Usage** - `'required'`)

[//]: # (- **Parameters** - **Don't** use parameters)

[//]: # ()
[//]: # (`string`)

[//]: # (- **Description** - Simple validation for **string** attributes)

[//]: # (- **Usage** - `'string'`, `['string', 30]`, `['string', 10, 200]`)

[//]: # (- **Parameters** - `min`, `max`, all parameters are optional)

[//]: # ()
[//]: # (`time`)

[//]: # (- **Description** - An validation for **time** attributes)

[//]: # (- **Usage** - `'time'`, `['time', 'H:i:s']`)

[//]: # (- **Parameters** - `format`, you can set the format for validation, the default format is set in `.env` like `TIME_FORMAT=H:i:s`)

[//]: # ()
[//]: # (`unique`)

[//]: # (- **Description** - Validation for **unique** attributes)

[//]: # (- **Usage** - `['unique', 'users', 'email']`)

[//]: # (- **Parameters** - `table`, `column`, you need pass the table and column name for validate if the attribute is unique)

[//]: # ()
[//]: # (`url`)

[//]: # (- **Description** - Simple validation for **url** attributes)

[//]: # (- **Usage** - `'url'`)

[//]: # (- **Parameters** - **Don't** use parameters)

[//]: # ()
[//]: # (`Routes with static files`)

[//]: # ()
[//]: # (<h5><b> /|\ GET</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($route->get&#40;'/', $route->view&#40;'contact.php'&#41;&#41;;)

[//]: # (```)

[//]: # ()
[//]: # (````php)

[//]: # (You can pass the relative path of view or use the $route->view helper)

[//]: # (````)

[//]: # ()
[//]: # (`Using Smarty in Galaxy`)

[//]: # ()
[//]: # (<h5><b> /|\ Galaxy</b></h5>)

[//]: # (<p>Use variable in template</p>)

[//]: # ()
[//]: # (````html)

[//]: # (<h1>{{$var}}</h1>)

[//]: # (````)

[//]: # ()
[//]: # (```php)

[//]: # ($route->get&#40;'/', function &#40;&#41; use &#40;$galaxy&#41; {)

[//]: # (    $var = 1;)

[//]: # (    $galaxy->render&#40;'template_path', ['var' => $var]&#41;;)

[//]: # (}&#41;;)

[//]: # (```)

[//]: # ()
[//]: # (<hr>)

[//]: # (<p>Make comments</p>)

[//]: # ()
[//]: # (````html)

[//]: # (<h1>{*New comment are here*}</h1>)

[//]: # (````)

[//]: # ()
[//]: # (<hr>)

[//]: # (<br>)

[//]: # ()
[//]: # (`Create new Command with Cosmos`)

[//]: # ()
[//]: # (<h5><b> /|\ Instance Cosmos</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # (class Migrate extends Command)

[//]: # ({)

[//]: # (    private Cosmos $cosmos;)

[//]: # (    )
[//]: # (        public function __construct&#40;&#41;)

[//]: # (        {)

[//]: # (            $this->cosmos = new Cosmos&#40;&#41;;)

[//]: # (        })

[//]: # (    })

[//]: # (})

[//]: # (```)

[//]: # ()
[//]: # (<br>)

[//]: # (<h5><b> /|\ Start</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->start&#40; $with_time, $with_change_counter &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This function starts the command and allows you to choose two parameters: @@)

[//]: # (    * Runtime count)

[//]: # (    * Number of changes)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER             |      TYPE       | DESCRIPTION                                                             |  DEFAULT   |)

[//]: # (|-----------------------|:---------------:|-------------------------------------------------------------------------|:----------:|)

[//]: # (| $output               | OutputInterface | Pass the output to write to the terminal                                |     X      |)

[//]: # (| $width_time           |      bool       | Set whether the build time will be shown at the end of the command      |   false    |)

[//]: # (| $width_change_counter |      bool       | Set whether the success counter will be shown at the end of the command |   false    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # (<h5><b> /|\ Title</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->title&#40; $main_title, $secondary_title &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method mount and write the title of command @@)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER             |  TYPE  | DESCRIPTION                                                | DEFAULT |)

[//]: # (|-----------------------|:------:|------------------------------------------------------------|:-------:|)

[//]: # (| $main_title           | string | Set the first string to be show on the command line title  |    X    |)

[//]: # (| $secondary_title      | string | Set the second string to be show on the command line title |    X    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # (<h5><b> /|\ IndexRow</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->indexRow&#40; $first_index, $second_index &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method create and write a index @@)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER     |  TYPE  | DESCRIPTION                                                | DEFAULT |)

[//]: # (|---------------|:------:|------------------------------------------------------------|:-------:|)

[//]: # (| $first_index  | string | Set the first string to be show on the command line index  |    X    |)

[//]: # (| $second_index | string | Set the second string to be show on the command line index |    X    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # (<h5><b> /|\ FileSuccessRow</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->fileSuccessRow&#40; $filename,  $status &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method mount and write a filename success message @@)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER |  TYPE  | DESCRIPTION                                                          | DEFAULT |)

[//]: # (|-----------|:------:|----------------------------------------------------------------------|:-------:|)

[//]: # (| $filename | string | Set the filename that will appear at the start of the row on success |    X    |)

[//]: # (| $status   | string | defines the status that will appear at the end of the row on success |    X    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # (<h5><b> /|\ FileFailRow</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->fileFailRow&#40; $filename,  $status &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method mount and write a filename fail message @@)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER |  TYPE  | DESCRIPTION                                                       | DEFAULT |)

[//]: # (|-----------|:------:|-------------------------------------------------------------------|:-------:|)

[//]: # (| $filename | string | Set the filename that will appear at the start of the row on fail |    X    |)

[//]: # (| $status   | string | defines the status that will appear at the end of the row on fail |    X    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # (<h5><b> /|\ Finish</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->finish&#40;&#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method is required to finish the command @@)

[//]: # (```)

[//]: # ()
[//]: # (<h5><b> /|\ CommandSuccess</b></h5>)

[//]: # ()
[//]: # (```php)

[//]: # ($cosmos->commandSuccess&#40; $main_title &#41;: void)

[//]: # (```)

[//]: # ()
[//]: # (```diff)

[//]: # (@@ This method write the success message @@)

[//]: # (```)

[//]: # ()
[//]: # (| PARAMETER |  TYPE  | DESCRIPTION                                                      | DEFAULT |)

[//]: # (|-----------|:------:|------------------------------------------------------------------|:-------:|)

[//]: # (| $filename | string | Set the string to be show on the command line title with success |    X    |)

[//]: # ()
[//]: # (<br>)

[//]: # ()
[//]: # ()
