<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductDetail;
use AppBundle\Form\ProductDetailType;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/catalog")
 */
class CatalogController extends Controller {

    /**
     * @Route("/", name="catalog_homepage")
     */
    public function indexAction(Request $request) {
        return $this->render('catalog/index.html.twig');
    }

    /**
     * @Route("/search", name="catalog_search")
     */
    public function searchAction(Request $request) {

        $searchTerms = $request->get('searchTerms');

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM AppBundle:Product p WHERE p.itemNumber = :searchTerms OR p.name LIKE :searchTermsLike OR p.barcode = :searchTerms";
        $query = $em->createQuery($dql)
                ->setParameter('searchTerms', $searchTerms)
                ->setParameter('searchTermsLike', "%" . $searchTerms . "%")
                ->setMaxResults(10);

        $products = $query->getResult();

        return $this->render('catalog/search.html.twig', [
                    'searchTerms' => $searchTerms,
                    'products' => $products
        ]);
    }

    /**
     * @Route("/edit/{id}", name="catalog_edit")
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

        return $this->render('catalog/edit.html.twig', [
                    'product' => $product,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="catalog_remove")
     */
    public function removeAction($id, Request $request) {
        $searchTerms = $request->get('searchTerms');
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('catalog_search', ['searchTerms' => $searchTerms]);
    }

}
