<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\AssociationOverrides({
 *  @ORM\AssociationOverride(name="collection", inversedBy="founders")
 * })
 */
class CollectionFounderMember extends CollectionMember
{

}