<?php

namespace AppBundle\Service;

use DateTime;

class ReportService {

    private $orderService;
    private $productService;

    public function __construct(OrderService $orderService, ProductService $productService) {
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

    public function generateReports() {

        // get two weeks
        $startDate = new DateTime("3 weeks ago");
        $endDate = new DateTime("1 week ago");

        $this->generateWebsiteReports($startDate, $endDate);
    }

    /**
     * 
     * @param array $orders
     * @param string $company
     * @return array
     */
    public function calculateWebsiteAverageDaysToShip(array $orders) {

        $daysToShip = [
            'Sunday' => [],
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => []
        ];

        foreach ($orders as $order) {

            if (count($order->getShipments()) > 0 && $order->getShipments()[0]->getShippingDate() !== null) {
                $interval = $order->getShipments()[0]->getShippingDate()->diff($order->getOrderDate());
                $daysToShip[$order->getOrderDate()->format('l')][] = $interval->d;
            }
        }

        $avgDaysToShip = [
            'Sunday' => 0,
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0
    ];

        foreach ($daysToShip as $key => $value) {
            if (count($value) > 0 && is_array($value)) {
                $avgDaysToShip[$key] = array_sum($value) / count($value);
            }
        }

        return $avgDaysToShip;
    }

    public function calculateAverageNumberOfOrders(array $orders) {

        $numberOfOrders = [
            'Sunday' => 0,
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0
        ];

        $weeks = [];

        foreach ($orders as $order) {
            $week = $order->getOrderDate()->format('W');
            if (array_search($week, $weeks) !== false) {
                $weeks[] = $week;
            }
            $numberOfOrders[$order->getOrderDate()->format('l')] ++;
        }

        $weekCount = (count($weeks) > 0) ? count($weeks) : 1;

        $avgNumberOfOrders = [];

        // divide by number of weeks
        foreach ($numberOfOrders as $key => $value) {
            $avgNumberOfOrders[$key] = $value / $weekCount;
        }

        return $avgNumberOfOrders;
    }

    public function calculateOrdersPerHour(array $orders) {

        $ordersPerHour = [];

        foreach ($orders as $order) {
            if (!isset($ordersPerHour[$order->getOrderDate()->format('Y-m-d H:00')])) {
                $ordersPerHour[$order->getOrderDate()->format('Y-m-d H:00')] = 0;
            }
            $ordersPerHour[$order->getOrderDate()->format('Y-m-d H:00')] ++;
        }

        return $ordersPerHour;
    }

    public function generateWebsiteReports(DateTime $startDate, DateTime $endDate) {

        $williamsOrders = $this->orderService->getWebsiteOrdersByDate($startDate, $endDate, OrderService::WMS_WILLIAMS);
        $muffsOrders = $this->orderService->getWebsiteOrdersByDate($startDate, $endDate, OrderService::WMS_MUFFS);

        $williamsAvgDaysToShip = $this->calculateWebsiteAverageDaysToShip($williamsOrders);
        $muffsAvgDaysToShip = $this->calculateWebsiteAverageDaysToShip($muffsOrders);

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $avgDaysToShip = [
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null]
        ];

        foreach ($days as $i => $day) {
            $avgDaysToShip[$i]['label'] = $day;
            $avgDaysToShip[$i]['muffs'] = $muffsAvgDaysToShip[$day];
            $avgDaysToShip[$i]['williams'] = $williamsAvgDaysToShip[$day];
        }

        file_put_contents(__DIR__ . '/../../../web/data/' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '-avgDaysToShip.json', json_encode($avgDaysToShip));

        $williamsAvgNumberOfOrders = $this->calculateAverageNumberOfOrders($williamsOrders);
        $muffsAvgNumberOfOrders = $this->calculateAverageNumberOfOrders($muffsOrders);

        $avgOrdersPerDay = [
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null],
            ['label' => null, 'muffs' => null, 'williams' => null]
        ];

        foreach ($days as $i => $day) {
            $avgOrdersPerDay[$i]['label'] = $day;
            $avgOrdersPerDay[$i]['muffs'] = $muffsAvgNumberOfOrders[$day];
            $avgOrdersPerDay[$i]['williams'] = $williamsAvgNumberOfOrders[$day];
        }

        file_put_contents(__DIR__ . '/../../../web/data/' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '-avgOrdersPerDay.json', json_encode($avgOrdersPerDay));

        $williamsOrdersPerHour = $this->calculateOrdersPerHour($williamsOrders);
        $muffsOrdersPerHour = $this->calculateOrdersPerHour($muffsOrders);

        $ordersPerHour = [];

        foreach ($days as $day) {

            $hours = array_unique(array_merge(array_keys($williamsOrdersPerHour[$day]), array_keys($muffsOrdersPerHour[$day])));

            foreach ($hours as $hour) {
                $ordersPerHour[] = [
                    'label' => $hour,
                    'muffs' => isset($muffsOrdersPerHour[$hour]) ? $muffsOrdersPerHour[$hour] : 0,
                    'williams' => isset($williamsOrdersPerHour[$hour]) ? $williamsOrdersPerHour[$hour] : 0
                ];
            }
        }

        file_put_contents(__DIR__ . '/../../../web/data/ordersPerHour.json', json_encode($ordersPerHour));
    }

}
