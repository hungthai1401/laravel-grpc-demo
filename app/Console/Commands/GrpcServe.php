<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\GRPC\Server as GrpcServer;
use Spiral\RoadRunner\GRPC\Invoker;
use App\Services\UserService;
use Generated\User\UserServiceInterface;

class GrpcServe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grpc:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the gRPC server';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $worker = Worker::create();
        $invoker = new Invoker();
        $server = new GrpcServer($invoker, [
            'debug' => false,
        ]);

        $server->registerService(UserServiceInterface::class, new UserService());

        // The server will use the configuration from .rr.yaml or environment
        $server->serve($worker);

        return 0;
    }
}
