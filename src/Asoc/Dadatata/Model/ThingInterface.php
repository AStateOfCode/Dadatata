<?php

namespace Asoc\Dadatata\Model;

interface ThingInterface {

    public function getKey();
    public function setKey($key);

    public function getMime();
    public function setMime($mime);

    public function getSize();
    public function setSize($size);

    public function setHashes(array $hashes);
    public function getHashes();
    public function setHash($type, $hash);

    public function addVariant($name, ThingInterface $thing);
    public function removeVariant($name);
    public function getVariant($name);
    public function getVariants();
    public function hasVariant($name);

    public function setFragments($count);
    public function getFragments();

}