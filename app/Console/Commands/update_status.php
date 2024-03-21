<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\CURL\Transaction;
use App\Models\PaymentInvoice;
use App\Models\UserTransactionDetails;


class update_status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update passport transaction status';

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
       $transactions =  UserTransactionDetails::where("transaction_type","passport")->where("status","Pending")->get();
       $invoices = PaymentInvoice::where("")->get();
       foreach($transactions as $trans){
            $transaction = Transaction::getTransaction($trans->transaction_id,"id");

            $status = $transaction['response']['status'];
            $processDate = $transaction['response']['processDate'];
            UserTransactionDetails::where("id",$trans->id)->update(["status"=>$status,"process_date"=>$processDate]);
        }
    }
}
