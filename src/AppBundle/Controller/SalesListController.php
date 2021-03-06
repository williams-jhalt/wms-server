<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @Route("/sales-list")
 */
class SalesListController extends Controller {

    /**
     * @Route("/", name="sales_list_index")
     */
    public function indexAction(Session $session) {

        $productService = $this->get('app.product_service');

        $products = $session->get("sales-list", []);

        return $this->render('sales-list/index.html.twig', [
                    'products' => $products
        ]);
    }

    /**
     * @Route("/remove-product", name="sales_list_remove")
     */
    public function removeProductAction(Request $request, Session $session) {

        $item = $request->get('itemNumber');
        $list = $session->get("sales-list", []);

        unset($list[$item]);

        $session->set('sales-list', $list);

        return $this->redirectToRoute('sales_list_index');
    }

    /**
     * @Route("/import-products", name="sales_list_import")
     */
    public function importListAction(Request $request, Session $session) {

        $productService = $this->get('app.product_service');

        $input = preg_split("/[\s,]+/", $request->get('import'));
        $list = $session->get('sales-list', []);

        foreach ($input as $key) {

            $itemNumber = trim($key);

            if (!empty($itemNumber)) {
                $result = $productService->findBySearchTerms($itemNumber);

                if (sizeof($result) == 1) {
                    $list[$result[0]->getItemNumber()] = $result[0];
                }
            }
        }

        $session->set('sales-list', $list);

        return $this->redirectToRoute("sales_list_index");
    }

    /**
     * @Route("/add-product", name="sales_list_add")
     */
    public function addProductAction(Request $request, Session $session) {

        $productService = $this->get('app.product_service');

        $search = $request->get('search');

        $results = $productService->findBySearchTerms($search);

        if (sizeof($results) > 1) {

            return $this->render('sales-list/choose.html.twig', [
                        'products' => $results
            ]);
        }

        $list = $session->get("sales-list", []);

        $list[$results[0]->getItemNumber()] = $results[0];

        $session->set("sales-list", $list);

        return $this->redirectToRoute("sales_list_index");
    }

    /**
     * @Route("/export", name="sales_list_export")
     */
    public function exportListAction() {

        $response = new StreamedResponse();
        $response->setCallback(function() {

            $session = $this->get('session');

            $items = $session->get('sales-list', []);

            $handle = fopen('php://output', 'w+');

            fputcsv($handle, ['Item Number', 'Description', 'Type', 'Vendor', 'Barcode', 'Url']);

            $productService = $this->get('app.product_service');

            foreach ($items as $product) {

                $row = [
                    $product->getItemNumber(),
                    $product->getName(),
                    $product->getProductType()->getCode(),
                    $product->getManufacturer()->getCode(),
                    $product->getBarcode(),
                    "https://williamstradingco.com/WMS/catalog/productDetail.php?sku=" . $product->getItemNumber()
                ];

                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv;charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }

}
