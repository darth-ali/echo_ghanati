<?php

namespace WP;
class WPTaxonomy
{
    private int    $ID;
    private string $name = '';
    private string $slug = '';


    /**
     * WPTaxonomy constructor.
     * @param int $ID
     */
    public function __construct(int $ID = 0)
    {

    }
}