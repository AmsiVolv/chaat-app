<?php

use Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel
 */
class AppKernel extends Kernel
{
    public function registerBundles(): array
    {
        $bundles = [
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
//            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
//            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
//            new Nelmio\CorsBundle\NelmioCorsBundle(),
//            new BackOfficeBundle\BackOfficeBundle(),
//            new AppBundle\AppBundle(),
//            new BackOfficeApiBundle\BackOfficeApiBundle(),
//            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
//            new CmfRoutingBundle(),
//            new APY\DataGridBundle\APYDataGridBundle(),
//            new Ekino\NewRelicBundle\EkinoNewRelicBundle(),
//            new ShortUrlBundle\ShortUrlBundle(),
//            new FrontendBundle\FrontendBundle(),
//            new Symfony\WebpackEncoreBundle\WebpackEncoreBundle()
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
//            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
