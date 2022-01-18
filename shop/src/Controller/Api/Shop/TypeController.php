<?php

namespace App\Controller\Api\Shop;

use App\Entity\Shop\Product;
use App\Entity\Shop\Type;
use App\Repository\Shop\ProductRepository;
use App\Repository\Shop\TypeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class TypeController extends AbstractController
{
    private TypeRepository $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    #[Route('/admin/shop/types/add', name: 'shop_type_add', methods: ['POST'])]
    public function add(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);

        $constraints = $this->getConstraint();

        try {
            $this->validate($constraints, $parameters);

            $type = Type::add($parameters['name']);
            $this->typeRepository->add($type);
            $this->typeRepository->flush();

            return $this->json([
                'data' => $type,
            ], 201);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        }
    }

    #[Route('/admin/shop/types/{id}/update', name: 'shop_type_edit', methods: ['PUT'])]
    public function update(Request $request, int $id)
    {
        $parameters = json_decode($request->getContent(), true);

        $constraints = $this->getConstraint();

        try {
            $type = $this->typeRepository->get($id);

            $this->validate($constraints, $parameters);

            $type->edit($parameters['name']);
            $this->typeRepository->add($type);
            $this->typeRepository->flush();

            return $this->json([
                'data' => $type,
            ], 201);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        }
    }

    #[Route('/admin/shop/types/{id}/remove', name: 'shop_type_delete', methods: ['DELETE'])]
    public function remove(Request $request, int $id)
    {
        try {
            $type = $this->typeRepository->get($id);

            $this->typeRepository->remove($type);
            $this->typeRepository->flush();

            return $this->json([
                'data' => $type,
            ], 201);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        }
    }

    private function getConstraint(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'Your first name must be at least 3 characters long',
                    'maxMessage' => 'Your first name cannot be longer than 255 characters',
                ]),
            ],
        ]);
    }

    private function validate(Assert\Collection $constraints, ?array $parameters)
    {
        $validator = Validation::createValidator();
        $validationResult = $validator->validate($parameters ?? [], $constraints);
        if ($validationResult->count() > 0) {
            throw new ValidationFailedException('Error in validating request', $validationResult);
        }
    }
}