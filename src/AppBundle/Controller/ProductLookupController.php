<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductDetail;
use AppBundle\Form\ProductDetailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/product-lookup")
 */
class ProductLookupController extends Controller {

    /**
     * @Route("/", name="product_lookup_index")
     */
    public function indexAction(Request $request) {
        return $this->render('product-lookup/index.html.twig');
    }

    /**
     * @Route("/search", name="product_lookup_search")
     */
    public function searchAction(Request $request) {
        
        $service = $this->get('app.product_service');
        $items = $service->findBySearchTerms($request->get('searchTerms'));
        
        return $this->render('product-lookup/search.html.twig', ['items' => $items]);
    }
    
    /**
     * @Route("/committed", name="product_lookup_committed")
     */
    public function committedAction(Request $request) {
        
        $page = $request->get('page', 1);
        
        $service = $this->get('app.product_service');
        
        $limit = 25;
        $offset = (($page - 1) * $limit);
        
        $items = $service->getCommittedProducts($offset, $limit);
        
        return $this->render('product-lookup/committed.html.twig', ['items' => $items, 'page' => $page]);
        
    }

    /**
     * @Route("/edit/{id}", name="product_lookup_edit")
     */
    public function editAction($id, Request $request) {

        $searchTerms = $request->get('searchTerms');
        
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        
        $detail = $product->getDetail();
        
        if ($detail == null) {
            $detail = new ProductDetail();
        }

        $form = $this->createForm(ProductDetailType::class, $detail);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product->setDetail($detail);
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('catalog_search', ['searchTerms' => $searchTerms]);
        }

        return $this->render('product-lookup/edit.html.twig', [
                    'product' => $product,
                    'form' => $form->createView()
        ]);
    }

}
