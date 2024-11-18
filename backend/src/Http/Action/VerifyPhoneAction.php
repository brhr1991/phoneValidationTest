<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\JsonResponse;
use App\Services\PhoneVerificationService;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly class VerifyPhoneAction implements RequestHandlerInterface
{
    public function __construct(private PhoneVerificationService $phoneVerificationService)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $data = json_decode((string)$request->getBody(), true, 512, JSON_THROW_ON_ERROR);

            if (empty($data['phone'])) {
                throw new InvalidArgumentException("Phone number is required.");
            }

            $verificationResponse = $this->phoneVerificationService->verifyPhone($data['phone']);

            return new JsonResponse($verificationResponse);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            return new JsonResponse(['error' => 'An internal error occurred'], 500);
        }
    }
}