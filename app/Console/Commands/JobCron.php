<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GrupoTelegram;

class JobCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Job ABAP DOJO';

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
     */
    public function handle()
    {
        \Log::info("Job diário executado");
        $grupos = GrupoTelegram::all();
         
        foreach ($grupos as $grupo) {
            \Log::info("Enviado para o grupo". $grupo->chatid );
            GrupoTelegram::getSapBlogPosts($grupo);
        }
        return 0;
    }
}
