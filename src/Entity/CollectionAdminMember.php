<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\AssociationOverrides({
 *  @ORM\AssociationOverride(name="collection", inversedBy="admins")
 * })
 */
class CollectionAdminMember extends CollectionMember
{

}