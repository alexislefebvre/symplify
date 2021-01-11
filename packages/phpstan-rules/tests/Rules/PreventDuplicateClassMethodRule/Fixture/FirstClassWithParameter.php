<?php

declare(strict_types=1);

namespace Symplify\PHPStanRules\Tests\Rules\PreventDuplicateClassMethodRule\Fixture;

class FirstClassWithParameter
{
    /**
     * @param object $x
     */
    public function someMethod(object $x)
    {
        echo 'statement';
        return $x->execute() && $x->getResult();
    }
}
