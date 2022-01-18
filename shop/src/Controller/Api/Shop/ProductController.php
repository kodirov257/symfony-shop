<?php

namespace App\Controller\Api\Shop;

use App\Entity\Shop\Product;
use App\Repository\Shop\ProductRepository;
use App\Repository\Shop\TypeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private TypeRepository $typeRepository;

    public function __construct(ProductRepository $productRepository, TypeRepository $typeRepository)
    {
        $this->productRepository = $productRepository;
        $this->typeRepository = $typeRepository;
    }

    #[Route('/admin/shop/products/add', name: 'shop_product_add', methods: ['POST'])]
    public function add(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);

        $constraints = $this->getConstraints();

        try {
            $this->validate($constraints, $parameters);

            $product = Product::add($parameters['name'], $parameters['price'], $parameters['description'] ?? null);
            if (isset($parameters['types']) && !empty($parameters['types'])) {
                foreach ($parameters['types'] as $type) {
                    $productType = $this->typeRepository->get($type['id']);
                    $product->addType($productType);
                }
            }
            $this->productRepository->add($product);
            $this->productRepository->flush();

            return $this->json([
                'data' => $product,
            ], 201);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    #[Route('/admin/shop/products/{id}/update', name: 'shop_product_update', methods: ['PUT'])]
    public function update(Request $request, int $id)
    {
        $parameters = json_decode($request->getContent(), true);

        $constraints = $this->getConstraints();

        try {
            $product = $this->productRepository->get($id);
            $this->validate($constraints, $parameters);

            $product->edit($parameters['name'], $parameters['price'], $parameters['description'] ?? null);
            $this->productRepository->add($product);
            $this->productRepository->flush();

            return $this->json([
                'data' => $product,
            ], 201);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    #[Route('/admin/shop/products/{id}/remove', name: 'shop_product_remove', methods: ['DELETE'])]
    public function remove(Request $request, int $id)
    {
        try {
            $product = $this->productRepository->get($id);

            $this->productRepository->remove($product);
            $this->productRepository->flush();

            return $this->json([
                'data' => $product,
            ], 200);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    #[Route('/admin/shop/products/{id}/add-type', name: 'shop_product_update', methods: ['POST'])]
    public function addType(Request $request, int $id)
    {
        $parameters = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'type_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'integer']),
            ],
        ]);

        try {
            $product = $this->productRepository->get($id);
            $this->validate($constraints, $parameters);
            $type = $this->typeRepository->get($parameters['type_id']);
            $product->addType($type);

            $this->productRepository->add($product);
            $this->productRepository->flush();

            return $this->json([
                'data' => $product,
            ], 201);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'violations' => $e->getViolations(),
            ], 400);
        } catch (EntityNotFoundException $e) {
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    private function getConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'Your first name must be at least 3 characters long',
                    'maxMessage' => 'Your first name cannot be longer than 255 characters',
                ]),
            ],
            'price' => [
                new Assert\Type('float'),
                new Assert\NotBlank(),
                new Assert\GreaterThanOrEqual([
                    'value' => 0.0,
                ]),
            ],
            'description' => [
                new Assert\Type('string')
            ],
            'types' => [
                new Assert\Type('array'),
//                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'id' => [
                            new Assert\NotBlank(),
                            new Assert\Type(['type' => 'integer'])
                        ],
                    ]),
                ]),
            ]
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