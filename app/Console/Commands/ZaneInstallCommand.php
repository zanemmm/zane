<?php

namespace App\Console\Commands;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Console\Command;

class ZaneInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zane:install {--user=zane} {--pass=zane}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install zane blog';

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
     * @return mixed
     */
    public function handle()
    {
        $this->initDatabase();
    }

    public function initDatabase()
    {
        $this->call('migrate:refresh');

        Administrator::create([
            'username' => $this->option('user'),
            'password' => bcrypt($this->option('pass')),
            'name'     => $this->option('user'),
        ]);

        $this->call('db:seed', ['--class' => \ZaneSeeder::class]);
    }
}
