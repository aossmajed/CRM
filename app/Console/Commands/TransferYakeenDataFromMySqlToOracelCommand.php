<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransferYakeenDataServices;
class TransferYakeenDataFromMySqlToOracelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yakeen:TransferYakeenDataFromMySqlToOracelCommand';

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
        TransferYakeenDataServices::TransferYakeenDataFromMySqlToOracel();
        return Command::SUCCESS;
    }
}
