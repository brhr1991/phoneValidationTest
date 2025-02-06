<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use App\Services\TonClient;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Olifanton\Interop\Address;
use Olifanton\Interop\KeyPair;
use Olifanton\Interop\Units;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\Contracts\Wallets\Transfer;
use Olifanton\Ton\Contracts\Wallets\V3\WalletV3Options;
use Olifanton\Ton\Contracts\Wallets\V3\WalletV3R1;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4Options;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4R2;
use Olifanton\Ton\Contracts\Wallets\V5\WalletV5Beta;
use Olifanton\Ton\Contracts\Wallets\V5\WalletV5Options;
use Olifanton\Ton\Contracts\Wallets\V5\WalletV5TransferOptions;
use Olifanton\Ton\Contracts\Wallets\Wallet;
use Olifanton\Ton\SendMode;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TonAction implements RequestHandlerInterface
{
    private ToncenterHttpV2Client $tonCenterClient;
    private Wallet $wallet;
    private KeyPair $keyPair;

    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $mnemonic = 'fossil alert evoke travel relief amazing escape marine bread test fine ripple call hidden woman dice cruise wagon object tenant camera father dance merit';
        $kp = TonMnemonic::mnemonicToKeyPair(explode(" ", trim($mnemonic)));

        $httpClient = new HttpMethodsClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
        );

        $this->tonCenterClient = new ToncenterHttpV2Client(
            $httpClient,
            new ClientOptions(
                ClientOptions::TEST_BASE_URL,
            ),
        );

        return new JsonResponse(['balance' => 'SUCCESS']);
    }
}