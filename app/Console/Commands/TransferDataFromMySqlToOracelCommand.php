<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransferDataServices;
class TransferDataFromMySqlToOracelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TransferDataFromMySqlToOracel:TransferDataFromMySqlToOracel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TransferDataServices::TransferDataFromMySqlToOracel();
        return Command::SUCCESS;
    }
}
