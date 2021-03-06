<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle;

use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\DataFixturesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\OverrideServicesPass;
use RichCongress\Bundle\UnitBundle\DependencyInjection\Compiler\PublicServicesPass;
use RichCongress\Bundle\UnitBundle\Utility\FixturesManager;
use RichCongress\Bundle\UnitBundle\Utility\OverrideServicesUtility;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class RichCongressUnitBundle
 *
 * @package   RichCongress\Bundle\UnitBundle
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class RichCongressUnitBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DataFixturesPass());
        $container->addCompilerPass(new PublicServicesPass());
        $container->addCompilerPass(new OverrideServicesPass());
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        // Mock services for the main container before loading fixtures
        OverrideServicesUtility::mockServices($this->container);

        // Autowire everything for the FixturesManager before the first test
        $this->container->get(FixturesManager::class);
    }
}
