<?php

namespace App\Console\Commands\RuntimeConnections;

//use CTDesarrollo\Regulus\AppSettingRepository;
//use CTDesarrollo\Regulus\Helpers\RuntimeConnection;
//use CTDesarrollo\Regulus\Traits\DBHelper;
use Illuminate\Console\Command;
use Throwable;

/**
 * Class AppCacheClear
 * @package App\Console\Commands
 */
class Store extends Command
{
    //use DBHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runtime-connections:store {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores a runtime connections';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Throwable
     */
    public function handle()
    {
        $this->withTransactions(function (){
            $driver = $this->choice('driver: ', ['mysql', 'pgsql', 'mongodb']);

            $id = $this->argument('id');

            switch ($driver){
                case 'mysql':
                    $host = $this->ask('host: ');
                    $username = $this->ask('username: ');
                    $password = $this->secret('password: ');
                    $database = $this->ask('database');
                    $port = $this->ask('port: ', 3306);
                    $charset = $this->ask('charset: ', 'utf8mb4');
                    $collation = $this->ask('collation: ', 'utf8mb4_unicode_ci');

                    $values = RuntimeConnection::storeFromArray($driver, compact('host', 'username', 'password', 'database', 'port', 'charset', 'collation'));

                    $repository = new AppSettingRepository();
                    $repository->set('runtime_connections', $id, $values);

                    /** @var RuntimeConnection $connection */
                    $connection = RuntimeConnection::makeFromArray($id)->first();
                    $connection->db()->select('select version()');

                    $this->info('runtime connection stored');

                    break;

                case 'psql':
                    $host = $this->ask('host: ');
                    $username = $this->ask('username: ');
                    $password = $this->secret('password: ');
                    $database = $this->ask('database');
                    $port = $this->ask('port: ', 5432);
                    $charset = $this->ask('charset: ', 'utf8');

                    $values = RuntimeConnection::storeFromArray($driver, compact('host', 'username', 'password', 'database', 'port', 'charset'));

                    $repository = new AppSettingRepository();
                    $repository->set('runtime_connections', $id, $values);

                    /** @var RuntimeConnection $connection */
                    $connection = RuntimeConnection::makeFromArray($id)->first();
                    $connection->db()->select('SELECT CURRENT_DATE');

                    $this->info('runtime connection stored');

                    break;

                case 'mongodb':
                    $dns = $this->ask('dsn: ');
                    $database = $this->ask('database: ');

                    $values = RuntimeConnection::storeFromArray($driver, compact('dns', 'database'));

                    $repository = new AppSettingRepository();
                    $repository->set('runtime_connections', $id, $values);

                    /** @var RuntimeConnection $connection */
                    $connection = RuntimeConnection::makeFromArray($id)->first();
                    $connection->db()->getPdo();

                    $this->info('runtime connection stored');
                    break;

                default:
                    $this->warn('invalid driver');
                    break;
            }
        });

        return Command::SUCCESS;
    }
}
