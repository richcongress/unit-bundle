<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rich_congress_unit');
        $rootNode = \method_exists(TreeBuilder::class, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('rich_congress_unit');

        $rootNode
            ->children()
                ->booleanNode('enable_db_caching')->defaultValue(true)->end()
                ->scalarNode('mocked_services')->defaultNull()->end()

            ->arrayNode('default_stubs')
                    ->children()
                        ->booleanNode('logger')->defaultFalse()->end()
                    ->end()
                ->end()

                ->arrayNode('public_services')
                    ->example(['logger', 'App/Repository/UserRepository'])
                    ->scalarPrototype()->end()
                ->end()

                ->arrayNode('test_roles')
                ->normalizeKeys(false)
                    ->useAttributeAsKey('key')
                    ->example(['NotLogged' => '', 'Admin' => 'user_1'])
                    ->scalarPrototype()->end()
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
