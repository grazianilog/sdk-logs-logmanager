# sdk-logs

## Como Configurar
- Este pacote utiliza como base o jenssegers/laravel-mongodb
- Utilize o comando 
```
composer install .
```
- Registar o Provider
```
LogManager\SdkLogs\SdkLogsServiceProvider::class,
```
- Registar o Aliases
```
'SDKLogs' => LogManager\SdkLogs\Facades\SDKLogs::class,
```
- Criar o arquivo de configuração deste package, com o comando 
```
php artisan vendor:publish --provider="LogManager\SdkLogs\SdkLogsServiceProvider" --tag="config"
```
- Adicone a coneção `mongodb` na config database
```
'mongodb' => [
    'driver' => 'mongodb',
    'host' => env('MONGO_DB_HOST', 'logmanager-mongodb-production.infra.logmanager.com.br'),
    'port' => env('MONGO_DB_PORT', 27017),
    'database' => env('MONGO_DB_DATABASE', 'homestead'),
    'username' => env('MONGO_DB_USERNAME', 'homestead'),
    'password' => env('MONGO_DB_PASSWORD', 'secret'),
    'options' => [
        'database' => env('DB_AUTH_DATABASE', 'homestead')
    ],
],
```
