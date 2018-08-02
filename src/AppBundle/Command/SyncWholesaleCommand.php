<?php

namespace AppBundle\Command;

use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttachment;
use AppBundle\Entity\ProductDetail;
use AppBundle\Entity\ProductType;
use DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WholesaleBundle\Service\WholesaleService;

class SyncWholesaleCommand extends ContainerAwareCommand {
    
    private $wholesale;
    
    /**
     * @return WholesaleService
     */
    private function getWholesale() {
        if ($this->wholesale == null) {
            $this->wholesale = $this->getContainer()->get('williams_wholesale.service');
        }
        return $this->wholesale;
    }

    protected function configure() {
        $this->setName('app:sync:wholesale')
                ->setDescription('Loads product data from Wholesale site.')
                ->setHelp('This command loads product data from Wholesale site if it does not exist in the current database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $output->writeln("Beginning Synchronization");

        $wholesale = $this->getWholesale();
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $limit = 100;
        $offset = 0;
        $count = 0;

        $output->writeln("Loading Manufacturers");

        $mfgRepo = $this->getContainer()->get('doctrine')->getRepository(Manufacturer::class);

        do {

            $manufacturers = $wholesale->getManufacturerRepository()->findAll($limit, $offset);
            
            foreach ($manufacturers->getItems() as $prod) {
                $mfg = $mfgRepo->findOneByCode($prod->getCode());
                if ($mfg == null) {
                    $mfg = new Manufacturer();
                    $mfg->setCode($prod->getCode());
                }
                $mfg->setName($prod->getName());
                $em->persist($mfg);
                $count++;
            }

            $output->writeln($count . " manufacturers have been loaded.");

            $offset += $limit;
        } while (sizeof($manufacturers->getItems()) > 0);

        $em->flush();

        $offset = 0;
        $count = 0;

        $output->writeln("Loading Types");

        $typeRepo = $this->getContainer()->get('doctrine')->getRepository(ProductType::class);

        do {

            $types = $wholesale->getProductTypeRepository()->findAll($limit, $offset);

            foreach ($types->getItems() as $prod) {
                $type = $typeRepo->findOneByCode($prod->getCode());
                if ($type == null) {
                    $type = new ProductType();
                    $type->setCode($prod->getCode());
                }
                $type->setName($prod->getName());
                $em->persist($type);
                $count++;
            }

            $output->writeln($count . " types have been loaded.");

            $offset += $limit;
        } while (sizeof($types->getItems()) > 0);

        $em->flush();

        $offset = 0;
        $count = 0;

        $repo2 = $this->getContainer()->get('doctrine')->getRepository(ProductAttachment::class);

        $output->writeln("Loading Products");

        do {
            
            $wProducts = $wholesale->getProductRepository()->findAll($limit, $offset);
            
            foreach ($wProducts->getItems() as $wProduct) {
                $product = $this->getContainer()->get('doctrine')->getRepository(Product::class)->findOneByItemNumber($wProduct->getSku());
                
                if ($product == null) {
                    continue;
                }

                $detail = $product->getDetail();

                $detail->setName($this->cleanName($prod->getName()));
                $detail->setDescription($prod->getDescription());
                $detail->setPackageHeight($prod->getHeight());
                $detail->setPackageLength($prod->getLength());
                $detail->setPackageWidth($prod->getWidth());
                $detail->setPackageLength($prod->getDiameter());
                $detail->setPackageWeight($prod->getWeight());
                $detail->setBrand($prod->getBrand());

                $attributes = $detail->getAttributes();

                $attributes['color'] = $prod->getColor();
                $attributes['material'] = $prod->getMaterial();
                $attributes['product_length'] = $prod->getProductLength();
                $attributes['insertable_length'] = $prod->getInsertableLength();
                $attributes['realistic'] = $prod->getRealistic();
                $attributes['has_balls'] = $prod->getBalls();
                $attributes['suction_cup'] = $prod->getSuctionCup();
                $attributes['harness'] = $prod->getHarness();
                $attributes['vibrating'] = $prod->getVibrating();
                $attributes['double_ended'] = $prod->getDoubleEnded();
                $attributes['circumference'] = $prod->getCircumference();
                
                $product->setDetail($detail);

                $em->persist($product);

                $output->writeln(sprintf("Imported %s", $product->getItemNumber()));

                $count++;

                if (($count % 100) == 0) {
                    $em->flush();
                }
            }

            $em->flush();
            $em->clear();

            $offset += $limit;
            
        } while (($offset - $limit) <= sizeof($wProducts->getItems()));

        $output->writeln($count . " products have been imported.");
    }

    private function cleanName($input) {

        $output = preg_replace("/\(d\)/i", "", $input);
        $output = preg_replace("/out .*/i", "", $output);

        $output = ucwords(strtolower($output));

        return $output;
    }

}
