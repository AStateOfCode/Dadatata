<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\ThingInterface;

interface LibraryInterface
{
    /**
     * Extracts file metadata from given data. If no existing metadata holder object is given, it will create a
     * new one.
     *
     * @param string|\SplFileInfo $data File path (string) or file object
     * @param ThingInterface $thing Update metadata on this thing.
     * @return ThingInterface
     */
    public function identify($data, ThingInterface $thing = null);

    /**
     * (relative) File path to the variant fragment.
     *
     * @param ThingInterface $thing The real thing
     * @param string $variant Variant name
     * @param int $fragment Variant fragment
     * @param bool $relative Relative instead of absolute
     * @return string Relative file path
     */
    public function getVariantPath(ThingInterface $thing, $variant, $fragment = 1, $relative = false);

    /**
     * Store the data as a variant for a thing.
     *
     * @param ThingInterface $thing The real thing
     * @param string $variant Variant name
     * @param string|\SplFileInfo $data File path (string) or file object
     * @return ThingInterface
     */
    public function storeVariant(ThingInterface $thing, $variant, $data);

    /**
     * Retrieve the data of the variant fragment.
     *
     * @param ThingInterface $thing The reak thing
     * @param string $variant Variant name
     * @param int $fragment Variant fragment
     * @return mixed Contents of the thing variant fragment
     */
    public function fetchVariant(ThingInterface $thing, $variant, $fragment = 1);

    /**
     * Store the data as new thing.
     *
     * @param string|\SplFileInfo $data File path (string) or file object
     * @param ThingInterface $thing Use existing metadata holder object
     * @return ThingInterface The metadata holder object that references the data
     */
    public function store($data, ThingInterface $thing = null);

    /**
     * Delete the metadata and contents.
     *
     * @param ThingInterface $thing
     */
    public function remove(ThingInterface $thing);

    /**
     * Relative path to the thing fragment.
     *
     * @param ThingInterface $thing The real thing
     * @param int $fragment
     * @param bool $relative Relative instead of absolute
     * @return string Relative file path
     */
    public function getPath(ThingInterface $thing, $fragment = 1, $relative = false);

    /**
     * Retrieve the data of the thing fragment.
     *
     * @param ThingInterface $thing The reak thing
     * @param int $fragment Variant fragment
     * @return mixed Contents of the thing fragment
     */
    public function fetch(ThingInterface $thing, $fragment = 1);

    /**
     * Delete the metadata and contents of a variant.
     *
     * @param ThingInterface $thing The real thing
     * @param string $variant Variant name
     */
    public function removeVariant(ThingInterface $thing, $variant);
}