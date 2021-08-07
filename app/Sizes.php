<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Sizes extends ReadOnlyBase
{
	  /**
     * @var string
     * @SWG\Property(property="32",type="string")
     * @SWG\Property(property="34",type="string")
     * @SWG\Property(property="36",type="string")
     * @SWG\Property(property="38",type="string")
     * @SWG\Property(property="40",type="string")
     * @SWG\Property(property="42",type="string")
     * @SWG\Property(property="44",type="string")
     */
	protected $data  = ['32','34','36','38','40','42','44'];
}
