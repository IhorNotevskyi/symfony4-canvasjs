<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductController extends Controller
{
    /**
     * @Route("/", name="product_list")
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $queryBuilder = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->createQueryBuilder('bp')
        ;

        $query = $queryBuilder
            ->orderBy('bp.id', 'DESC')
            ->getQuery()
        ;

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)
        );

        $totalPages = ceil($products->getTotalItemCount() / $products->getItemNumberPerPage());
        if ($request->query->get('page') > $totalPages) {
            throw $this->createNotFoundException('Запрошенная страница не найдена');
        }

        return ['products' => $products];
    }

    /**
     * @Route("/add", name="product_add")
     * @Template()
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->add('Сохранить изменения', SubmitType::class, [
            'attr' => ['class' => 'btn btn-lg btn-success']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            foreach ($product->getInterspaces() as $interspace){
                $interspace->setProduct($product);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Сохранено');

            return $this->redirectToRoute('product_add');
        }

        return ['product_form' => $form->createView()];
    }

    /**
     * @Route("/edit/{id}", name="product_edit", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function editAction(Product $product, Request $request)
    {
        $originalInterspaces = new ArrayCollection();
        foreach ($product->getInterspaces() as $interspace) {
            $originalInterspaces->add($interspace);
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->add('Сохранить изменения', SubmitType::class, [
            'attr' => ['class' => 'btn btn-lg btn-success']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();

            foreach ($product->getInterspaces() as $interspace){
                $interspace->setProduct($product);
            }

            foreach ($originalInterspaces as $interspace) {
                if (false === $product->getInterspaces()->contains($interspace)) {
                    $em->persist($interspace);
                    $em->remove($interspace);
                }
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Сохранено');

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return ['product' => $product, 'product_form' => $form->createView()];
    }

    /**
     * @Route("/delete/{id}", name="product_delete", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->deleteProduct($product)
        ;

        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/first-way/{id}", name="product_first_way", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function firstWayAction(Product $product, Request $request)
    {
        $form = $this
            ->createFormBuilder()
            ->add('search', DateType::class, [
                'widget' => 'choice',
                'label' => false,
                'format' => 'ddMMyyyy',
                'years' => range(2013,2050),
                'placeholder' => [
                    'day' => 'День', 'month' => 'Месяц', 'year' => 'Год'
                ]
            ])
            ->add('Определить', SubmitType::class, [
                'attr' => ['class' => 'btn-sx btn-primary first_button_' . $product->getId()]
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('product_first_way', ['id' => $product->getId()]);
        }

        return ['product' => $product, 'form' => $form->createView()];
    }

    /**
     * @Route("/first-price/{id}", name="product_first_price", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function firstPriceAction(Product $product, Request $request)
    {
        $formData = $request->get('form');

        dump($formData); die;

        $daysInterval = range(1, 31);
        $monthsInterval = range(1, 12);
        $yearsInterval = range(2013, 2050);

        if (!in_array($formData['search']['day'], $daysInterval) || !in_array($formData['search']['month'], $monthsInterval) || !in_array($formData['search']['year'], $yearsInterval)) {
            $errorMessage = 'Вы заполнили не все поля или ввели некорректные данные';

            return $this->json(['errorMessage' => $errorMessage]);
        }

        $day = mb_strlen($formData['search']['day']) === 1 ? '0' . $formData['search']['day'] : $formData['search']['day'];
        $month = mb_strlen($formData['search']['month']) === 1 ? '0' . $formData['search']['month'] : $formData['search']['month'];
        $year = $formData['search']['year'];

        $date = $year . '-' . $month . '-' . $day;
        $date = new \DateTime($date);

        if ($date < $product->getCreatedAt()) {
            $errorMessage = 'Продукт был создан позже введенной Вами даты';

            return $this->json(['errorMessage' => $errorMessage]);
        }

        $intervalsAndPrices = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getFirstCurrentPrice($product, $date)
        ;

        if (count($intervalsAndPrices) === 0 || !$intervalsAndPrices[0]['price']) {
            $currentPrice = $product->getBasePrice();

            return $this->json(['currentPrice' => $currentPrice]);
        } elseif (count($intervalsAndPrices) === 1 && $intervalsAndPrices[0]['price']) {
            $currentPrice = $intervalsAndPrices[0]['price'];

            return $this->json(['currentPrice' => $currentPrice]);
        } elseif (count($intervalsAndPrices) > 1) {
            $intervals = [];

            foreach ($intervalsAndPrices as $intervalAndPrice) {
                $intervals[] = $intervalAndPrice['difference'];
            }

            $minInterval = min($intervals);
            $numberOfRepeatedValues = count(array_keys($intervals, $minInterval));

            if ($numberOfRepeatedValues === 1) {
                $minIntervalKey = array_search($minInterval, $intervals);
                $currentPrice = $intervalsAndPrices[$minIntervalKey]['price'];

                return $this->json(['currentPrice' => $currentPrice]);
            } elseif ($numberOfRepeatedValues > 1) {
                $errorMessage = 'Пересеклось несколько цен с одинаковым меньшим периодом действия';

                return $this->json(['errorMessage' => $errorMessage]);
            }
        }
    }

    /**
     * @Route("/first-chart/{id}", name="first_chart", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function firstChartAction(Product $product)
    {
        $dataForChart = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getDataForFirstChart($product)
        ;

        $staticDataForChart = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getStaticDataForChart($product)
        ;

        $basePrice = (int)$product->getBasePrice();
        $createdAt = $product->getCreatedAt()->getTimestamp() * 1000;
        $currentDate = (new \DateTime($staticDataForChart[0]['currentDate']))->getTimestamp() * 1000;

        $oneDay = 86400000;
        $chartData = [];
        $priceChangePoints = [];

        $countNullValues = count(array_keys($dataForChart[0], null, true));

        if ($countNullValues === 4) {
            $chartData[] = ['price' => $basePrice, 'date' => $createdAt];
            $chartData[] = ['price' => $basePrice, 'date' => $currentDate];

            return $this->json($chartData);
        }

        foreach ($dataForChart as $data) {
            $priceChangePoints[] = $data['startDate']->getTimestamp() * 1000;
            $priceChangePoints[] = $data['finishDate']->getTimestamp() * 1000 + $oneDay;
        }

        $priceChangePoints = array_unique($priceChangePoints, SORT_NUMERIC);
        sort($priceChangePoints);

        if ($priceChangePoints[0] < $createdAt) {
            $chartData[] = ['error' => 'Продукт был создан позже даты начала акции'];

            return $this->json($chartData);
        } elseif ($priceChangePoints[0] > $createdAt) {
            array_unshift($priceChangePoints, $createdAt);
        }

        if ($priceChangePoints[count($priceChangePoints) - 1] < $currentDate) {
            $priceChangePoints[] = $currentDate;
        } elseif ($priceChangePoints[count($priceChangePoints) - 1] > $currentDate) {
            $duplicatePriceChangePoints = [];

            foreach ($priceChangePoints as $data) {
                if ($data >= $currentDate) {
                    continue;
                } else {
                    $duplicatePriceChangePoints[] = $data;
                }
            }

            $duplicatePriceChangePoints[] = $currentDate;
            $priceChangePoints = $duplicatePriceChangePoints;
        }

        foreach ($priceChangePoints as $date) {
            $date = new \DateTime(date('Y-m-d', $date / 1000));

            $intervalsAndPrices = $this
                ->getDoctrine()
                ->getRepository('App:Product')
                ->getFirstCurrentPrice($product, $date)
            ;

            $date = $date->getTimeStamp() * 1000;

            if (count($intervalsAndPrices) === 0 || !$intervalsAndPrices[0]['price']) {
                $chartData[] = ['price' => $basePrice, 'date' => $date];
            } elseif (count($intervalsAndPrices) === 1 && $intervalsAndPrices[0]['price']) {
                $chartData[] = ['price' => (int)$intervalsAndPrices[0]['price'], 'date' => $date];
            } elseif (count($intervalsAndPrices) > 1) {
                $intervals = [];

                foreach ($intervalsAndPrices as $intervalAndPrice) {
                    $intervals[] = $intervalAndPrice['difference'];
                }

                $minInterval = min($intervals);
                $numberOfRepeatedValues = count(array_keys($intervals, $minInterval));

                if ($numberOfRepeatedValues === 1) {
                    $minIntervalKey = array_search($minInterval, $intervals);
                    $currentPrice = (int)$intervalsAndPrices[$minIntervalKey]['price'];
                    $chartData[] = ['price' => $currentPrice, 'date' => $date];

                } elseif ($numberOfRepeatedValues > 1) {
                    $chartData[] = ['error' => 'Пересеклось несколько цен с одинаковым меньшим периодом действия'];
                }
            }
        }

        foreach ($chartData as $data) {
           if (key($data) === "error") {
               $chartData = [];
               $chartData[] = $data;

               return $this->json($chartData);
           }
        }

        return $this->json($chartData);
    }

    /**
     * @Route("/second-way/{id}", name="product_second_way", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function secondWayAction(Product $product, Request $request)
    {
        $form = $this
            ->createFormBuilder()
            ->add('search', DateType::class, [
                'widget' => 'choice',
                'label' => false,
                'format' => 'ddMMyyyy',
                'years' => range(2013,2050),
                'placeholder' => [
                    'day' => 'День', 'month' => 'Месяц', 'year' => 'Год'
                ]
            ])
            ->add('Определить', SubmitType::class, [
                'attr' => ['class' => 'btn-sx btn-primary second_button_' . $product->getId()]
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('product_second_way', ['id' => $product->getId()]);
        }

        return ['product' => $product, 'form' => $form->createView()];
    }

    /**
     * @Route("/second-price/{id}", name="product_second_price", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function secondPriceAction(Product $product, Request $request)
    {
        $formData = $request->get('form');

        $daysInterval = range(1, 31);
        $monthsInterval = range(1, 12);
        $yearsInterval = range(2013, 2050);

        if (!in_array($formData['search']['day'], $daysInterval) || !in_array($formData['search']['month'], $monthsInterval) || !in_array($formData['search']['year'], $yearsInterval)) {
            $errorMessage = 'Вы заполнили не все поля или ввели некорректные данные';

            return $this->json(['errorMessage' => $errorMessage]);
        }

        $day = mb_strlen($formData['search']['day']) === 1 ? '0' . $formData['search']['day'] : $formData['search']['day'];
        $month = mb_strlen($formData['search']['month']) === 1 ? '0' . $formData['search']['month'] : $formData['search']['month'];
        $year = $formData['search']['year'];

        $date = $year . '-' . $month . '-' . $day;
        $date = new \DateTime($date);

        if ($date < $product->getCreatedAt()) {
            $errorMessage = 'Продукт был создан позже введенной Вами даты';

            return $this->json(['errorMessage' => $errorMessage]);
        }

        $intervalsAndPrices = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getSecondCurrentPrice($product, $date)
        ;

        if (count($intervalsAndPrices) === 0 || !$intervalsAndPrices[0]['price']) {
            $currentPrice = $product->getBasePrice();

            return $this->json(['currentPrice' => $currentPrice]);
        } elseif (count($intervalsAndPrices) === 1 && $intervalsAndPrices[0]['price']) {
            $currentPrice = $intervalsAndPrices[0]['price'];

            return $this->json(['currentPrice' => $currentPrice]);
        } elseif (count($intervalsAndPrices) > 1) {
            $creationDates = [];

            foreach ($intervalsAndPrices as $intervalAndPrice) {
                $creationDates[] = $intervalAndPrice['startDate']->format('Y-m-d');
            }

            $numberOfRepeatedValues = count(array_keys($creationDates, $creationDates[0]));

            if ($numberOfRepeatedValues === 1) {
                $currentPrice = $intervalsAndPrices[0]['price'];

                return $this->json(['currentPrice' => $currentPrice]);
            } elseif ($numberOfRepeatedValues > 1) {
                $errorMessage = 'Пересеклось несколько цен с одинаковой датой установления';

                return $this->json(['errorMessage' => $errorMessage]);
            }
        }
    }

    /**
     * @Route("/second-chart/{id}", name="second_chart", requirements={"id": "[0-9]+"})
     * @Template()
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function secondChartAction(Product $product)
    {
        $dataForChart = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getDataForSecondChart($product)
        ;

        $staticDataForChart = $this
            ->getDoctrine()
            ->getRepository('App:Product')
            ->getStaticDataForChart($product)
        ;

        $basePrice = (int)$product->getBasePrice();
        $createdAt = $product->getCreatedAt()->getTimestamp() * 1000;
        $currentDate = (new \DateTime($staticDataForChart[0]['currentDate']))->getTimestamp() * 1000;

        $oneDay = 86400000;
        $chartData = [];
        $priceChangePoints = [];

        $countNullValues = count(array_keys($dataForChart[0], null, true));

        if ($countNullValues === 3) {
            $chartData[] = ['price' => $basePrice, 'date' => $createdAt];
            $chartData[] = ['price' => $basePrice, 'date' => $currentDate];

            return $this->json($chartData);
        }

        foreach ($dataForChart as $data) {
            $priceChangePoints[] = $data['startDate']->getTimestamp() * 1000;
            $priceChangePoints[] = $data['finishDate']->getTimestamp() * 1000 + $oneDay;
        }

        $priceChangePoints = array_unique($priceChangePoints, SORT_NUMERIC);
        sort($priceChangePoints);

        if ($priceChangePoints[0] < $createdAt) {
            $chartData[] = ['error' => 'Продукт был создан позже даты начала акции'];

            return $this->json($chartData);
        } elseif ($priceChangePoints[0] > $createdAt) {
            array_unshift($priceChangePoints, $createdAt);
        }

        if ($priceChangePoints[count($priceChangePoints) - 1] < $currentDate) {
            $priceChangePoints[] = $currentDate;
        } elseif ($priceChangePoints[count($priceChangePoints) - 1] > $currentDate) {
            $duplicatePriceChangePoints = [];

            foreach ($priceChangePoints as $data) {
                if ($data >= $currentDate) {
                    continue;
                } else {
                    $duplicatePriceChangePoints[] = $data;
                }
            }

            $duplicatePriceChangePoints[] = $currentDate;
            $priceChangePoints = $duplicatePriceChangePoints;
        }

        foreach ($priceChangePoints as $date) {
            $date = new \DateTime(date('Y-m-d', $date / 1000));

            $intervalsAndPrices = $this
                ->getDoctrine()
                ->getRepository('App:Product')
                ->getSecondCurrentPrice($product, $date)
            ;

            $date = $date->getTimeStamp() * 1000;

            if (count($intervalsAndPrices) === 0 || !$intervalsAndPrices[0]['price']) {
                $chartData[] = ['price' => $basePrice, 'date' => $date];
            } elseif (count($intervalsAndPrices) === 1 && $intervalsAndPrices[0]['price']) {
                $chartData[] = ['price' => (int)$intervalsAndPrices[0]['price'], 'date' => $date];
            } elseif (count($intervalsAndPrices) > 1) {
                $creationDates = [];

                foreach ($intervalsAndPrices as $intervalAndPrice) {
                    $creationDates[] = $intervalAndPrice['startDate']->format('Y-m-d');
                }

                $numberOfRepeatedValues = count(array_keys($creationDates, $creationDates[0]));

                if ($numberOfRepeatedValues === 1) {
                    $chartData[] = ['price' => (int)$intervalsAndPrices[0]['price'], 'date' => $date];
                } elseif ($numberOfRepeatedValues > 1) {
                    $chartData[] = ['error' => 'Пересеклось несколько цен с одинаковой датой установления'];
                }
            }
        }

        foreach ($chartData as $data) {
            if (key($data) === "error") {
                $chartData = [];
                $chartData[] = $data;

                return $this->json($chartData);
            }
        }

        return $this->json($chartData);
    }
}