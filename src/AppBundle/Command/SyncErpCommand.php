<?php

namespace AppBundle\Command;

use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductType;
use DateTime;
use ErpBundle\Service\ErpService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncErpCommand extends ContainerAwareCommand {

    private $erp;

    /**
     * @return ErpService
     */
    private function getErpService() {
        if ($this->erp == null) {
            $this->erp = $this->getContainer()->get('williams_erp.service');
        }
        return $this->erp;
    }

    protected function configure() {
        $this->setName('app:sync:erp')
                ->setDescription('Loads new products from ERP and updates existing ones.')
                ->setHelp('This command loads all products from ERP into the catalog, new products will be created.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $output->writeln("Beginning Synchronization");

        $limit = 1000;
        $offset = 0;
        $count = 0;

        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $this->getContainer()->get('doctrine')->getRepository(Product::class);
        $typeRepo = $this->getContainer()->get('doctrine')->getRepository(ProductType::class);
        $mfgrRepo = $this->getContainer()->get('doctrine')->getRepository(Manufacturer::class);

        do {

            $products = $this->getErpService()->getProductRepository()->findAll($limit, $offset);

            foreach ($products->getProducts() as $prod) {

                $type = $typeRepo->findOneByCode($prod->getProductTypeCode());
                if ($type == null) {
                    $type = new ProductType();
                    $type->setCode($prod->getProductTypeCode());
                    $type->setName($prod->getProductTypeCode());
                    $em->persist($type);
                    $em->flush($type);
                }

                $mfgr = $mfgrRepo->findOneByCode($prod->getManufacturerCode());
                if ($mfgr == null) {
                    $mfgr = new Manufacturer();
                    $mfgr->setCode($prod->getManufacturerCode());
                    $mfgr->setName($prod->getManufacturerCode());
                    $em->persist($mfgr);
                    $em->flush($mfgr);
                }

                $product = $repo->findOneByItemNumber($prod->getItemNumber());
                if ($product == null) {
                    $product = new Product();
                    $product->setItemNumber($prod->getItemNumber());
                }
                $product->setName($prod->getName());
                $product->setReleaseDate($prod->getReleaseDate());
                $product->setBinLocation($prod->getBinLocation());
                $product->setManufacturer($mfgr);
                $product->setProductType($type);
                $product->setCreatedOn($prod->getCreatedOn());
                $product->setDeleted($prod->getDeleted());
                $product->setWebItem($prod->getWebItem());
                $product->setWarehouse($prod->getWarehouse());
                $product->setUnitOfMeasure($prod->getUnitOfMeasure());
                $product->setBarcode($prod->getBarcode());
                $product->setQuantityOnHand($prod->getQuantityOnHand());
                $product->setQuantityCommitted($prod->getQuantityCommitted());
                $product->setWholesalePrice($prod->getWholesalePrice());
                $em->persist($product);
                $count++;
            }

            $em->flush();
            $em->clear();

            $output->writeln($count . " products have been imported.");

            $offset += $limit;
        } while (sizeof($products->getProducts()) > 0);
    }

}
