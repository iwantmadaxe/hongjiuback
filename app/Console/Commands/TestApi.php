<?php

namespace App\Console\Commands;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\API\V1\BaseController;
use App\Jobs\ImportCardToDB;
use Illuminate\Console\Command;

class TestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
		$client = new Client();

		$requests = function ($total) {
			$uri = 'http://api.ct10649.com:9001/m2m_ec/query.do?method=queryTrafficOfToday&user_id=tzshxd&iccid=8986031641202258412&passWord=16D09A92E97BE2CEDD7F5497534ED423BF8A3CC46FBCC1AD&sign=16D09A92E97BE2CEDD7F5497534ED423F826F07D78F0D011D2CF72F4C3F87D3272CB0504CB9CBC55F146944E5E286E71917A50DF5C0ABE75EEC05E793A3AAD13F752252871875F00D6E33A003A1A9507AE0DFEB429B7D030D94B6E24AF283A45EC27620FC106F5B18DB9F0FFF9200A2C';
			echo time();
			for ($i = 0; $i < $total; $i++) {
				yield new Request('GET', $uri);
			}
			echo time();
		};

		$pool = new Pool($client, $requests(2), [
			'concurrency' => 10000,
			'fulfilled' => function ($response, $index) {
				$this->info("请求第 $index 个请求");
				// this is delivered each successful response
			},
			'rejected' => function ($reason, $index) {
				// this is delivered each failed request
			},
		]);

// Initiate the transfers and create a promise
		$promise = $pool->promise();
		$promise->wait();
    }
}
