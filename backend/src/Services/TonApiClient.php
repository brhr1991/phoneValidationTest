<?php

namespace App\Services;

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Olifanton\Interop\Address;
use Olifanton\Interop\KeyPair;
use Olifanton\Interop\Units;
use Olifanton\Mnemonic\TonMnemonic;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4Options;
use Olifanton\Ton\Contracts\Wallets\V4\WalletV4R2;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\Exceptions\ClientException;
use Olifanton\Ton\Transports\Toncenter\Exceptions\TimeoutException;
use Olifanton\Ton\Transports\Toncenter\Exceptions\ValidationException;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;

class TonApiClient
{
    private ToncenterHttpV2Client $tonCenter;
    private WalletV4R2 $wallet;
    private KeyPair $keyPair;
    private ToncenterTransport $transport;

    public function __construct(
        string $tatumApiKey,
        array $mnemonic,
        bool $testMode = false
    ) {
        $httpClient = new HttpMethodsClient(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory()
        );

        $this->tonCenter = new ToncenterHttpV2Client(
            $httpClient,
            new ClientOptions(
                ClientOptions::TEST_BASE_URL,
                ''
            )
        );

        $this->transport = new ToncenterTransport($this->tonCenter);
        $this->keyPair = TonMnemonic::mnemonicToKeyPair($mnemonic);
        $walletOptions = new WalletV4Options($this->keyPair->publicKey);
        $this->wallet = new WalletV4R2($walletOptions);
    }

    public function getBalance(string $address)
    {
        $balance = $this->tonCenter->getAddressBalance(new Address($address));
        return Units::fromNano($balance);
    }

    public function getTransactions(string $address, int $limit = 20): array
    {
        return $this->tonCenter->getTransactions(
            new Address($address),
            $limit
        )->items;
    }

    /**
     * @throws TimeoutException
     * @throws ClientException
     * @throws ValidationException
     */
    public function getAddressInformation(string $address)
    {
        return $this->tonCenter->getAddressInformation(new Address($address));
    }

    public function getMasterchainInfo()
    {
        return $this->tonCenter->getMasterchainInfo();
    }

    public function getWalletInformation()
    {
        return $this->tonCenter->getAddressInformation($this->wallet->getAddress());
    }
}