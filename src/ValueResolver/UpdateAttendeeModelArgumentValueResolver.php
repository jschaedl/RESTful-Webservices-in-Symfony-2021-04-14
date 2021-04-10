<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Domain\Model\UpdateAttendeeModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateAttendeeModelArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return UpdateAttendeeModel::class === $argument->getType() && 'PUT' === $request->getMethod();
    }

    /**
     * @return iterable<UpdateAttendeeModel>
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $model = $this->serializer->deserialize(
            $request->getContent(),
            UpdateAttendeeModel::class,
            $request->getRequestFormat(),
        );

        $validationErrors = $this->validator->validate($model);

        if (\count($validationErrors) > 0) {
            throw new ValidationFailedException($model, $validationErrors);
        }

        yield $model;
    }
}