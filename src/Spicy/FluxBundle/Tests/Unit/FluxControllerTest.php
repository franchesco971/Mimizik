<?php

namespace Spicy\FluxBundle\Tests\Unit;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Spicy\FluxBundle\Controller\FluxController;
use Spicy\ITWBundle\Entity\Interview;
use Spicy\ITWBundle\Entity\Repository\InterviewRepository;

class FluxControllerTest extends TestCase
{    
    /**
     * @group unit
     * @group flux
     * @test
     */
    // public function TestFluxITWAction()
    // {
    //     $interviews = [(new Interview)];
        
    //     $itwRepository = $this->createMock(InterviewRepository::class);
    //     $itwRepository->method('getAll')->with(10)
    //     ->willReturn( $interviews);
        
    //     $em = $this->createMock(EntityManagerInterface::class);
    //     $em->method('getRepository')->with(Interview::class)
    //          ->willReturn($itwRepository);

    //     $containerMock = $this->createMock(ContainerInterface::class);
    //     $containerMock->method('get')->with('router')->willReturn('url');

    //     // $fluxControllerMock = $this->createMock(FluxController::class)->method('generateUrl')->willReturn('url');
    //     // $fluxControllerMock->fluxITWAction($em);

    //     $fluxController = new FluxController();
    //     $fluxController->setContainer($containerMock);
    //     $fluxController->fluxITWAction($em);

    //     $this->assertTrue(true);
    // }
}