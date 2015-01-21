<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Page;

class LoadData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $page = new Page();
        $page
            ->setTitle('Rest API Symfony 2.6')
            ->setBody('Heres another nice guide on how to create an API with Symfony 2.6');

        $manager->persist($page);

        $manager->flush();
    }
}
