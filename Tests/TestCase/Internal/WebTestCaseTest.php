<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use Mockery\MockInterface;
use RichCongress\Bundle\UnitBundle\Exception\ContainerNotEnabledException;
use RichCongress\Bundle\UnitBundle\Exception\DuplicatedContainersException;
use RichCongress\Bundle\UnitBundle\TestCase\Internal\WebTestCase;
use RichCongress\Bundle\UnitBundle\TestConfiguration\Annotation\WithContainer;
use RichCongress\Bundle\UnitBundle\Tests\Resources\Command\DummyCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class WebTestCaseTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestCase\Internal
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 *
 * @covers    \RichCongress\Bundle\UnitBundle\TestCase\Internal\WebTestCase
 * @covers    \RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility
 */
class WebTestCaseTest extends WebTestCase
{
    /**
     * @WithContainer
     *
     * @return void
     */
    public function testConstructor(): void
    {
        self::$container = null;
        $testCase = new self();
        self::setUpBeforeClass();
        self::tearDownAfterClass();

        self::assertInstanceOf(WebTestCase::class, $testCase);
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testGetContainerFromOriginalWebTestCase(): void
    {
        $mockedService = $this->getService('security.token_storage');

        self::assertInstanceOf(TokenStorageInterface::class, $mockedService);
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testGetContainerCreation(): void
    {
        self::$container = null;
        $mockedService = $this->getService('security.token_storage');

        self::assertInstanceOf(TokenStorageInterface::class, $mockedService);
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testCreateClientAfterGetContainer(): void
    {
        $this->getContainer();

        $this->expectException(DuplicatedContainersException::class);

        self::createClient();
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testCreateTwoClientsInSameTest(): void
    {
        self::createClient();

        $this->expectException(DuplicatedContainersException::class);

        self::createClient();
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testExecuteCommand(): void
    {
        $output = $this->executeCommand(DummyCommand::$defaultName);

        self::assertStringContainsString('This is a DummyCommand', $output);
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testGetEntityManager(): void
    {
        /** @var WebTestCase|MockInterface $webTestCase */
        $webTestCase = \Mockery::mock(WebTestCase::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $webTestCase->setUp();

        $container = \Mockery::mock(ContainerInterface::class);
        $entityManager = \Mockery::mock(EntityManagerInterface::class);

        $container->shouldReceive('get')
            ->once()
            ->with(EntityManagerInterface::class)
            ->andReturn($entityManager);

        $webTestCase->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        self::assertSame($entityManager, $webTestCase->getManager());
    }

    /**
     * @WithContainer
     *
     * @return void
     */
    public function testGetEntityManagerWithoutDoctrine(): void
    {
        /** @var WebTestCase|MockInterface $webTestCase */
        $webTestCase = \Mockery::mock(WebTestCase::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $webTestCase->setUp();

        $container = \Mockery::mock(ContainerInterface::class);

        $container->shouldReceive('get')
            ->once()
            ->with(EntityManagerInterface::class)
            ->andThrow(new ServiceNotFoundException(''));

        $container->shouldReceive('get')
            ->once()
            ->with('doctrine')
            ->andThrow(new ServiceNotFoundException(''));

        $webTestCase->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        $this->expectException(ServiceNotFoundException::class);

        $webTestCase->getManager();
    }

    /**
     * @return void
     */
    public function testGetEntityManagerFromDoctrine(): void
    {
        /** @var WebTestCase|MockInterface $webTestCase */
        $webTestCase = \Mockery::mock(WebTestCase::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $webTestCase->setUp();

        $container = \Mockery::mock(ContainerInterface::class);
        $registry = \Mockery::mock(ManagerRegistry::class);
        $entityManager = \Mockery::mock(EntityManagerInterface::class);

        $container->shouldReceive('get')
            ->once()
            ->with(EntityManagerInterface::class)
            ->andThrow(new ServiceNotFoundException(''));

        $container->shouldReceive('get')
            ->once()
            ->with('doctrine')
            ->andReturn($registry);

        $registry->shouldReceive('getManager')
            ->once()
            ->andReturn($entityManager);

        $webTestCase->shouldReceive('getContainer')
            ->once()
            ->andReturn($container);

        self::assertSame($entityManager, $webTestCase->getManager());
    }

    /**
     * @return void
     */
    public function testGetContainerWithoutAnnotation(): void
    {
        $this->expectException(ContainerNotEnabledException::class);

        $this->getContainer();
    }
}
