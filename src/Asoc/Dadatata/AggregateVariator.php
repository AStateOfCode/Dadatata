<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Exception\FailedToGenerateVariantException;
use Asoc\Dadatata\Exception\FilterDoesNotSupportInputException;
use Asoc\Dadatata\Exception\NoFilterDefinedForVariantException;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;

class AggregateVariator implements VariatorInterface
{
    /**
     * @var VariatorInterface[]
     */
    private $variators;

    public function __construct(array $variators)
    {
        $this->variators = $variators;
    }

    public function generate(ThingInterface $thing, $variant, $sourcePath, OptionsInterface $options = null)
    {
        foreach ($this->variators as $variator) {
            if (!$variator->hasSupportFor($variant)) {
                continue;
            }

            try {
                return $variator->generate($thing, $variant, $sourcePath, $options);
            } catch (FilterDoesNotSupportInputException $e) {
                // intenionally silenced, we try the next variator
            } catch (FailedToGenerateVariantException $e) {
                // intenionally silenced, we try the next variator
            } catch (NoFilterDefinedForVariantException $e) {
                // intenionally silenced, we try the next variator
            }
        }

        throw new FailedToGenerateVariantException('None of the variators were able to generate the requested variant');
    }

    /**
     * @return array
     */
    public function getSupportedVariants()
    {
        $supported = [];
        foreach ($this->variators as $variator) {
            $supported = array_merge($supported, $variator->getSupportedVariants());
        }

        return $supported;
    }

    /**
     * @param string $variant Variant name
     *
     * @return bool
     */
    public function hasSupportFor($variant)
    {
        foreach ($this->variators as $variator) {
            if ($variator->hasSupportFor($variant)) {
                return true;
            }
        }

        return false;
    }
}