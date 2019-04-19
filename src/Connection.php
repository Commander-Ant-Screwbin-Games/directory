<?php
declare(strict_types=1);
/**
 * Kooser PasswordLock - Secure your password using password lock.
 * 
 * @package Kooser\PasswordLock.
 */

namespace Kooser\Directory;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Create a new database connection.
 */
class Connection implements ConnectionInterface
{

    /** @var array $options The database connection options. */
    private $options = [];

    /** @var bool $exceptions Should we utilize exceptions. */
    protected $exceptions = \true;

    /**
     * Construct a new database connection.
     *
     * @param array $options    The database connection options.
     * @param bool  $exceptions Should we utilize exceptions.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [], bool $exceptions = \true)
    {
        $this->exceptions = $exceptions;
        $this->setOptions($options);
    }

    /**
     * Set the database connection options.
     *
     * @param array $options The database connection options.
     *
     * @return void Returns nothing.
     */
    public function setOptions(array $options = []): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Configure the hasher options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'database_port' => 3306,
            'database_pass' => '',
        ]);
        $resolver->setRequired('database_dns');
        $resolver->setRequired('database_name');
        $resolver->setRequired('database_user');
        $resolver->setAllowedTypes('database_dns', 'string');
        $resolver->setAllowedTypes('database_name', 'string');
        $resolver->setAllowedTypes('database_user', 'string');
        $resolver->setAllowedTypes('database_pass', 'string');
        $resolver->setAllowedTypes('database_port', 'int');
    }
}
